<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of updateDrmsWithMouvementsTask
 *
 * @author mathurin
 */
class generateBilanDrmTask extends sfBaseTask {

    const ENTETES_ETABLISSEMENT = 'Identifiant;Raison Sociale;Nom Com.;Siret;Cvi;Num. Accises;Adresse;Code postal;Commune;Pays;Email;Tel.;Fax;Douane;Statut;Famille;Sous Famille;Zones;';

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('lastcampagne', null, sfCommandOption::PARAMETER_OPTIONAL, 'Campagne -1 pour le bilan', false),
        ));
        $this->addArguments(array(
            new sfCommandArgument('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'The interprofession name'),
            new sfCommandArgument('depot_dir', null, sfCommandOption::PARAMETER_REQUIRED, 'Emplacement des fichiers de sorties'),
        ));
        $this->namespace = 'generate';
        $this->name = 'bilanDRM';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $campagne = ($options['lastcampagne'])? (new CampagneManager('08'))->getCampagneByDate(date('Y-m-d', mktime(0, 0, 0, date("m"),   date("d"),   date("Y")-1))) : (new CampagneManager('08'))->getCurrent();

        $periodes = $this->getPeriodes($campagne);

        $interpro = InterproClient::getInstance()->retrieveById(str_replace('INTERPRO-', '', $arguments['interpro']));
        if (!$interpro) {
            echo "Interprofession requise\n";exit;
        }
        $depot = $arguments['depot_dir'];
        if (!is_dir($depot)||!is_writable($depot)) {
            echo "$depot doit être un dossier writable\n";exit;
        }

        $depot = "$depot/$campagne";

        if (file_exists($depot)) {
            array_map('unlink', glob("$depot/*.csv"));
        } else {
            mkdir("$depot", 0755);
        }

        $etablissements = array_merge(InterproClient::getInstance()->find('INTERPRO-IR')->getEtablissementsArrayFromGrcFile(), InterproClient::getInstance()->find('INTERPRO-CIVP')->getEtablissementsArrayFromGrcFile());

        $libellesStatuts = DRMClient::getAllLibellesStatusBilan();
        ksort($etablissements);

        // Initialisation des fichiers avec entetes
        $bilanCsv = $this->getEnteteBilanCsv($periodes);
        $bilanPeriodesCsv = [];
        foreach($periodes as $periode) {
            $bilanPeriodesCsv[$periode] = $this->getEnteteBilanPeriodeCsv($this->getPeriodeLastYear($periode));
        }

        // On peuple des données les fichiers
        foreach($etablissements as $etablissement) {
            if (!$this->inZone($interpro->zone, explode('|', $etablissement[EtablissementCsv::COL_ZONES]))) {
                continue;
            }
            $historique = new DRMHistorique($etablissement[EtablissementCsv::COL_ID]);
            if (!$this->isEligibleDRM($etablissement, $historique->getLastDRM())) {
                continue;
            }
            $drms = $historique->getDRMsByCampagne($campagne, true);
            $lastDrm = $historique->getLastDRM();
            $statuts = [];
            foreach($periodes as $periode) {
                if ($lastDrm && $lastDrm->periode < $periode && $lastDrm->hasStocksEpuise()) {
                    $statut = DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE;
                } elseif (isset($drms[$periode])) {
                    $statut = DRMClient::getInstance()->find($drms[$periode]->_id)->getStatutBilan();
                } else {
                    $statut = DRMClient::DRM_STATUS_BILAN_A_SAISIR;
                }
                $statuts[] = $libellesStatuts[$statut];
                // On peuple les données periodique N-1
                if (in_array($statut, [DRMClient::DRM_STATUS_BILAN_A_SAISIR,DRM_STATUS_BILAN_NON_VALIDE])) {
                    if ($drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement[EtablissementCsv::COL_ID], $this->getPeriodeLastYear($periode))) {
            			foreach ($drm->getDetails() as $detail) {
            				if ($detail->interpro != $interpro->_id) {
            					continue;
            				}
                            $str = $this->getEtablissementInfosCsv($etablissement);
            				$str .=  $detail->getCertification()->getKey().";";
            				$str .=  $detail->getGenre()->getCode().";";
            				$str .=  $detail->getAppellation()->getKey().";";
            				$str .=  $detail->getLieu()->getKey().";";
            				$str .=  $detail->getCouleur()->getKey().";";
            				$str .=  $detail->getCepage()->getKey().";";
            				$str .=  $detail->getStockBilan().";";
            				$str .=  $detail->total_debut_mois.";";
            				$str .=  $detail->sorties->vrac.";";
            				$str .=  $detail->sorties->export.";";
            				$str .=  $detail->sorties->factures.";";
            				$str .=  $detail->sorties->crd.";";
            				$str .=  $detail->sorties->vrac_export."\n";
                            $bilanPeriodesCsv[$periode] .= str_replace(DRM::DEFAULT_KEY, '', $str);
            			}
            		}
                }
            }
            $bilanCsv .= $this->getEtablissementInfosCsv($etablissement).implode(';', $statuts)."\n";
        }

        if (file_put_contents("$depot/bilan.csv", $bilanCsv) === false) {
            echo "l'ecriture dans $depot/bilan.csv à echoué\n";exit;
        }

        foreach($bilanPeriodesCsv as $periode => $bilanPeriodeCsv) {
            if (file_put_contents("$depot/".str_replace('-', '', $periode)."_bilan.csv", $bilanPeriodeCsv) === false) {
                echo "l'ecriture dans $depot/".str_replace('-', '', $periode)."_bilan.csv à echoué\n";exit;
            }
        }

        echo "Génération des bilans pour la campagne $campagne réalisée avec succès\n";exit;
    }

    private function getPeriodeLastYear($periode) {
        return (((int) substr($periode, 0,4) ) - 1 ).substr($periode, 4);
    }

    private function getEnteteBilanCsv($periodes) {
        return self::ENTETES_ETABLISSEMENT.implode(';', $periodes)."\n";
    }

    private function getEnteteBilanPeriodeCsv($periode) {
        return self::ENTETES_ETABLISSEMENT."Categorie;Genre;Denomination;Lieu;Couleur;Cepage;$periode;Total debut de mois;Vrac DAA/DAE;Conditionne Export;DSA / Tickets / Factures;CRD France;Vrac Export\n";
    }

    private function getEtablissementInfosCsv($etablissement) {
            $email = $etablissement[EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL] ?: $etablissement[EtablissementCsv::COL_EMAIL];
            return $etablissement[EtablissementCsv::COL_ID] . ';'
                    . $etablissement[EtablissementCsv::COL_RAISON_SOCIALE] . ';'
                    . $etablissement[EtablissementCsv::COL_NOM] . ';'
                    . $etablissement[EtablissementCsv::COL_SIRET] . ';'
                    . $etablissement[EtablissementCsv::COL_CVI] . ';'
                    . $etablissement[EtablissementCsv::COL_NO_ASSICES] . ';'
                    . $etablissement[EtablissementCsv::COL_ADRESSE] . ';'
                    . $etablissement[EtablissementCsv::COL_CODE_POSTAL] . ';'
                    . $etablissement[EtablissementCsv::COL_COMMUNE] . ';'
                    . $etablissement[EtablissementCsv::COL_PAYS] . ';'
                    . $email . ';'
                    . $etablissement[EtablissementCsv::COL_TELEPHONE] . ';'
                    . $etablissement[EtablissementCsv::COL_FAX] . ';'
                    . $etablissement[EtablissementCsv::COL_SERVICE_DOUANE] . ';'
                    . $etablissement[EtablissementCsv::COL_CHAMPS_STATUT] . ';'
                    . $etablissement[EtablissementCsv::COL_FAMILLE] . ';'
                    . $etablissement[EtablissementCsv::COL_SOUS_FAMILLE] . ';'
                    . $etablissement[EtablissementCsv::COL_ZONES_LIBELLES] . ';';
    }

    private function isEligibleDRM($etablissement, $lastDRM) {
        try {
            $famille = EtablissementClient::getInstance()->matchFamille(KeyInflector::slugify(trim($etablissement[EtablissementCsv::COL_FAMILLE])));
            $sousfamille = EtablissementClient::getInstance()->matchSousFamille(KeyInflector::slugify(trim($etablissement[EtablissementCsv::COL_SOUS_FAMILLE])));
        } catch (Exception $e) {
            return false;
        }
        $isActif = (trim($etablissement[EtablissementCsv::COL_CHAMPS_STATUT]) == Etablissement::STATUT_ACTIF);
        if (!$isActif) {
            return false;
        }
        if ($sousfamille == EtablissementFamilles::SOUS_FAMILLE_VENDEUR_RAISIN) {
            return false;
        }
        if ($famille == EtablissementFamilles::FAMILLE_NEGOCIANT && $sousfamille != EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR && !$lastDRM) {
            return false;
        }
        return ($famille == EtablissementFamilles::FAMILLE_PRODUCTEUR||$famille == EtablissementFamilles::FAMILLE_NEGOCIANT);
    }

    private function inZone($zone, $zones) {
        $client = ConfigurationZoneClient::getInstance();
        foreach ($zones as $z) {
            try {
                $zn = $client->matchZone(trim($z));
            } catch (Exception $e) {
                continue;
            }
            if ($zn == $zone) {
                return true;
            }
        }
        return false;
    }

    private function getPeriodes($campagne) {
        $periodes = array();
        $stopPeriode = date('Y-m');
        $months = array('08', '09', '10', '11', '12', '01', '02', '03', '04', '05', '06', '07');
        $years = explode('-', $campagne);
        foreach ($months as $month) {
            if ($month < 8) {
                $periode = $years[1] . '-' . $month;
            } else {
                $periode = $years[0] . '-' . $month;
            }
            if ($periode > $stopPeriode) {
                break;
            }
            $periodes[] = $periode;
        }
        return $periodes;
    }

}
