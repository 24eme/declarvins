<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MaintenanceTransfertChaiTask
 *
 * @author mathurin
 */
class ExportContratsFATask extends sfBaseTask {

    const INTERPRO = 'INTERPRO-IVSE';
    const STARTDATE = '2022-03-31T99:99:99';
    const ACCOMPTE = 0;

    const CSV_FA_NUM_LIGNE = 0;
    const CSV_FA_TYPE_CONTRAT = 1;
    const CSV_FA_CAMPAGNE = 2;
    const CSV_FA_NUM_ARCHIVAGE = 3;
    const CSV_FA_CODE_LIEU_VISA = 4;
    const CSV_FA_CODE_ACTION = 5;
    const CSV_FA_DATE_CONTRAT = 6;
    const CSV_FA_DATE_VISA = 7;
    const CSV_FA_CODE_COMMUNE_LIEU_VINIFICATION = 8;
    const CSV_FA_INDICATION_DOUBLE_FIN = 9;
    const CSV_FA_CODE_INSEE_DEPT_COMMUNE_ACHETEUR = 10;
    const CSV_FA_NATURE_ACHETEUR = 11;
    const CSV_FA_SIRET_ACHETEUR = 12;
    const CSV_FA_CVI_VENDEUR = 13;
    const CSV_FA_NATURE_VENDEUR = 14;
    const CSV_FA_SIRET_VENDEUR = 15;
    const CSV_FA_COURTIER = 16; // (O/N)
    const CSV_FA_DELAI_RETIRAISON = 17;
    const CSV_FA_POURCENTAGE_ACCOMPTE = 18;
    const CSV_FA_DELAI_PAIEMENT = 19;
    const CSV_FA_CODE_TYPE_PRODUIT = 20;
    const CSV_FA_CODE_DENOMINATION_VIN_IGP = 21;
    const CSV_FA_PRIMEUR = 22;
    const CSV_FA_BIO = 23;
    const CSV_FA_COULEUR = 24;
    const CSV_FA_ANNEE_RECOLTE = 25;
    const CSV_FA_CODE_ELABORATION = 26; // (O/N)
    const CSV_FA_VOLUME = 27;
    const CSV_FA_DEGRE = 28; //(Degré vin si type de contrat = V (vins) Degré en puissance si type de contrat = M (moût))
    const CSV_FA_PRIX = 29;
    const CSV_FA_UNITE_PRIX = 30; // H pour Hl
    const CSV_FA_CODE_CEPAGE = 31;
    const CSV_FA_CODE_DEST = 32; // Z
    const CSV_FA_LAST = 33; // 0

    protected $cm = null;
    protected $produitsConfiguration = null;
    protected $correspondancesInsee = array();
    protected $correspondancesCepages = array();
    protected static $regions = ['IVSE' => ['04', '05', '06', '13', '83', '84'], 'AURA' => ['07', '26', '38', '42', '43', '63', '69', '73', '74']];
    protected static $codeRegions = ['IVSE' => '030', 'AURA' => '060'];
    const DEFAULT_REGION = 'IVSE';
    const CORRESPONDANCES_INSEE_FILE = '/export/correspondance-insee-postal.csv';
    const CORRESPONDANCES_CEPAGES_FILE = '/export/correspondance-cepages.csv';

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('region', sfCommandArgument::REQUIRED, "Region"),
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('dryrun', null, sfCommandOption::PARAMETER_REQUIRED, 'Mode de test ne sauvgarde pas en base', false),
                // add your own options here
        ));

        $this->namespace = 'export';
        $this->name = 'contrats-france-agrimer';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [export-contrats-france-agrimer|INFO] task update contrat from chai src to chai dst.
Call it with:

  [php symfony export:contrats-france-agrimer|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $this->makeInseeCorrespondances();
        $this->makeCepagesCorrespondances();
        $this->cm = new CampagneManager('08-01');
        $this->produitsConfiguration = ConfigurationProduitClient::getInstance()->getByInterpro(self::INTERPRO);
        $interpro = self::INTERPRO;
        $region = strtoupper($arguments['region']);
        if (!in_array($region, array_keys(self::$regions))) {
            echo "Region '$region' inconnue";exit;
        }
        $contrats = $this->getContrats($interpro, self::STARTDATE);
        $this->printCSV($contrats->rows, $region, $options['dryrun']);
    }

    protected function makeInseeCorrespondances() {
        $file = sfConfig::get('sf_data_dir').self::CORRESPONDANCES_INSEE_FILE;
        if (file_exists($file)) {
            if (($handle = fopen($file, "r")) !== false) {
                while (($data = fgetcsv($handle, null, ";")) !== false) {
                    foreach(explode('/', $data[1]) as $cp) {
                        $this->correspondancesInsee[$cp] = $data[0];
                        $this->correspondancesInsee[$cp.KeyInflector::slugify($data[2])] = $data[0];
                    }
                }
                fclose($handle);
            }
        }
    }

    protected function makeCepagesCorrespondances() {
        $file = sfConfig::get('sf_data_dir').self::CORRESPONDANCES_CEPAGES_FILE;
        if (file_exists($file)) {
            if (($handle = fopen($file, "r")) !== false) {
                while (($data = fgetcsv($handle, null, ";")) !== false) {
                    $this->correspondancesCepages[KeyInflector::slugify($data[1])] = $data[2];
                }
                fclose($handle);
            }
        }
    }

    protected function getInsee($tiers) {
        if ($insee = $tiers->getCodeInsee()) {
            return $insee;
        }
        if (isset($this->correspondancesInsee[$tiers->siege->code_postal.KeyInflector::slugify($tiers->siege->commune)])) {
            return $this->correspondancesInsee[$tiers->siege->code_postal.KeyInflector::slugify($tiers->siege->commune)];
        }
        if (isset($this->correspondancesInsee[$tiers->siege->code_postal])) {
            return $this->correspondancesInsee[$tiers->siege->code_postal];
        }
        return null;
    }

    protected function getCepageCode($libelle) {
        if (isset($this->correspondancesCepages[KeyInflector::slugify($libelle)])) {
            return $this->correspondancesCepages[KeyInflector::slugify($libelle)];
        }
        return null;
    }

    protected function getContrats($interpro, $startDate) {

        return VracClient::getInstance()->retrieveAllVracs($interpro, $startDate);
    }

    protected function printCSV($contratsView, $region, $dryrun = false) {
        if (!count($contratsView)) {
            fwrite(STDERR, "Aucun contrats\n");
        }
        $cpt = 0;
        $departements = self::$regions[$region];
        $code = self::$codeRegions[$region];
        fwrite(STDERR, "#NUM_LIGNE;TYPE_CONTRAT;CAMPAGNE;NUM_ARCHIVAGE;CODE_LIEU_VISA;CODE_ACTION;DATE_CONTRAT;DATE_VISA;CODE_COMMUNE_LIEU_VINIFICATION;INDICATION_DOUBLE_FIN;CODE_INSEE_DEPT_COMMUNE_ACHETEUR;NATURE_ACHETEUR;SIRET_ACHETEUR;CVI_VENDEUR;NATURE_VENDEUR;SIRET_VENDEUR;COURTIER;DELAI_RETIRAISON;POURCENTAGE_ACCOMPTE;DELAI_PAIEMENT;CODE_TYPE_PRODUIT;CODE_DENOMINATION_VIN_IGP;PRIMEUR;BIO;COULEUR;ANNEE_RECOLTE;CODE_ELABORATION;VOLUME;DEGRE;PRIX;UNITE_PRIX;CODE_CEPAGE;CODE_DEST;ID_DOC;\n");
        fwrite(STDERR, "#num_ligne;type_contrat;campagne;num_archive;code_lieu_visa;code_action;date_contrat;date_visa;code_commune_lieu_vinification;indicateur_double_fin;code_insee_dept_commune_acheteur;nature_acheteur;siret_acheteur;cvi_vendeur;nature_vendeur;siret_vendeur;courtier (O/N);delai_retiraison;pourcentage_accompte;delai_paiement;code_type_produit;code_denomination_vin_IGP;primeur;bio;couleur;annee_recolte;code_elaboration (O/N);volume;degre (Degré vin si type de contrat = V (vins) Degré en puissance si type de contrat = M (moût));prix;unité_prix (H);code_cepage;code_dest (Z)\n");
        foreach ($contratsView as $contratView) {
            if (!preg_match('/\/[a-z]+\/[a-z]+\/IGP(.*)/', $contratView->key[VracDateView::KEY_PRODUIT_HASH])) {
                continue;
            }
            $contrat = VracClient::getInstance()->find($contratView->id);

            if (!$this->isContratATransmettre($contrat)) {
                continue;
            }

            $vendeur = $contrat->getVendeurObject();

            $cp = $contrat->vendeur->code_postal;

            if (!$cp) {
                $cp = $vendeur->siege->code_postal;
            }

            if (!$cp) {
                $cp = $this->getInsee($vendeur);
            }

            if ($cp && !in_array(substr($cp, 0, 2), $departements)) {
                continue;
            }

            if (!$cp && $code != self::$codeRegions[self::DEFAULT_REGION]) {
                continue;
            }

            $cpt++;
            $ligne = array();

            $produit = $this->produitsConfiguration->get($contratView->key[VracDateView::KEY_PRODUIT_HASH]);

            $acheteur = $contrat->getAcheteurObject();

            $ligne[self::CSV_FA_NUM_LIGNE] = "01";
            $type_contrat = "";
            if ($contrat->type_transaction == VracClient::TRANSACTION_DEFAUT) {
                $type_contrat = "V";
            } else {
                $type_contrat = "M";
            }
            $campagne = $this->cm->getCampagneByDate($contrat->valide->date_validation);
            $ligne[self::CSV_FA_TYPE_CONTRAT] = $type_contrat; // V pour vrac, M pour Mout
            $ligne[self::CSV_FA_CAMPAGNE] = substr($campagne, 0, 4);
            $ligne[self::CSV_FA_NUM_ARCHIVAGE] = substr($contrat->numero_contrat, 5); // Est-ce notre numéro d'archivage?
            $ligne[self::CSV_FA_CODE_LIEU_VISA] = $code;
            $ligne[self::CSV_FA_CODE_ACTION] = ($contrat->exist("versement_fa")) ? $contrat->versement_fa : 'NC'; // NC = Nouveau Contrat, SC = Supprimé Contrat, MC = Modifié Contrat
            $ligne[self::CSV_FA_DATE_CONTRAT] = Date::francizeDate($contrat->valide->date_saisie);
            $ligne[self::CSV_FA_DATE_VISA] = Date::francizeDate($contrat->valide->date_validation);

            $ligne[self::CSV_FA_CODE_COMMUNE_LIEU_VINIFICATION] = $this->getInsee($vendeur); // Code Insee Vendeur
            $ligne[self::CSV_FA_INDICATION_DOUBLE_FIN] = 'N'; // Quelle signification?
            /**
             * ACHETEUR
             */
            $ligne[self::CSV_FA_CODE_INSEE_DEPT_COMMUNE_ACHETEUR] = $this->getInsee($acheteur); // Code Insee Acheteur
            $ligne[self::CSV_FA_NATURE_ACHETEUR] = ($acheteur->exist('nature_inao'))? $acheteur->nature_inao : '09';
            $ligne[self::CSV_FA_SIRET_ACHETEUR] = preg_replace('/ /', '', $acheteur->siret);
            /**
             * VENDEUR
             */
            $ligne[self::CSV_FA_CVI_VENDEUR] = $vendeur->cvi;
            $ligne[self::CSV_FA_NATURE_VENDEUR] = ($vendeur->exist('nature_inao'))? $vendeur->nature_inao : '09';
            $ligne[self::CSV_FA_SIRET_VENDEUR] = preg_replace('/ /', '', $vendeur->siret);
            /**
             * COURTIER
             */
            $ligne[self::CSV_FA_COURTIER] = ($contrat->mandataire_exist) ? 'O' : 'N';

            $delai_retiraison = $this->diffDate($contrat->date_limite_retiraison, $contrat->date_debut_retiraison);

            $ligne[self::CSV_FA_DELAI_RETIRAISON] = sprintf("%0.1f", $delai_retiraison);
            $ligne[self::CSV_FA_POURCENTAGE_ACCOMPTE] = self::ACCOMPTE;
            $ligne[self::CSV_FA_DELAI_PAIEMENT] = $this->getDelaiPaiement($contrat);

            $ligne[self::CSV_FA_CODE_TYPE_PRODUIT] = ($type_contrat == 'M') ? 'E' : "PA";
            $ligne[self::CSV_FA_CODE_DENOMINATION_VIN_IGP] = $this->getCodeDenomVinIGP($produit); // ASSIGNER LES CODE PRODUITS IGP
            $ligne[self::CSV_FA_PRIMEUR] = ($contrat->isPrimeur()) ? "O" : "N";
            $ligne[self::CSV_FA_BIO] = ($contrat->isBio()) ? "O" : "N";
            $ligne[self::CSV_FA_COULEUR] = $this->getCouleurIGP($type_contrat, $produit);
            $ligne[self::CSV_FA_ANNEE_RECOLTE] = (substr($contrat->millesime, 0, 4))? substr($contrat->millesime, 0, 4) : substr($contrat->valide->date_saisie, 0, 4); //??
            $ligne[self::CSV_FA_CODE_ELABORATION] = 'N'; //DISABLED ($contrat->conditionnement_crd == 'NEGOCE_ACHEMINE') ? "P" : "N";
            $ligne[self::CSV_FA_VOLUME] = sprintf($contrat->volume_propose, '0.3f');
            $ligne[self::CSV_FA_DEGRE] = "12.0"; //DISABLED sprintf("%0.1f", $contrat->degre);
            $ligne[self::CSV_FA_PRIX] = sprintf($contrat->prix_unitaire, '0.2f');
            $ligne[self::CSV_FA_UNITE_PRIX] = 'H';
            $ligne[self::CSV_FA_CODE_CEPAGE] = $this->getCepageCode($produit->libelle);
            $ligne[self::CSV_FA_CODE_DEST] = ($type_contrat == 'M') ? 'E' : "Z";
            $ligne[self::CSV_FA_LAST] = "0.0";
            $ligne[self::CSV_FA_LAST + 1 ] = $contrat->_id;

             //export pour FA
            for($i = 0 ;  $i < count($ligne) - 1 ; $i++) {
                echo '"' . $ligne[$i] . '";';
            }
            echo "\n";
            //export pour le debug (contenant l'identifiant du doc)
            for($i = 0 ;  $i < count($ligne) ; $i++) {
                fwrite(STDERR, '"' . $ligne[$i] . '";');
            }
            fwrite(STDERR, "\n");
            $contrat->add('versement_fa', VracClient::VERSEMENT_FA_TRANSMIS);

            if($dryrun) {
                continue;
            }

            $contrat->save();
        }
    }

    public function diffDate($date1, $date2) {
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $diffMois = $datetime1->diff($datetime2)->m;
        return $diffMois;
    }

    protected function getDelaiPaiement($contrat) {
        switch ($contrat->delai_paiement) {
            case "60_jours":
                    return 2.0;
            case "30_jours":
                    return 1.0;
            case "45_jours":
                    return 1.5;
            default:
                return 'X';
        }
    }

    protected function getCodeDenomVinIGP($produit) {
        return sprintf('%03d', substr($produit->getIdentifiantDouane(), 2, 3));
    }

    protected function getCouleurIGP($type_contrat, $produit) {
        $couleur = $produit->getCouleur()->getKey();
        if ($type_contrat == 'M') {
            switch ($couleur) {
                case "blanc_sec":
                case "blanc_doux":
                case "blanc":
                    return "BL";
                default:
                    return "CO";
            }
        }
        switch ($couleur) {
            case "blanc_sec":
            case "blanc":
            case "blanc_doux":
                return "BL";
            case "rouge":
                return "RG";
            case "rose":
                return "RS";
        }
        return $couleur;
    }

    public function isContratATransmettre($contrat) {
        if ($contrat->type_transaction == 'raisin') {
            return false;
        }
        if (!$contrat->referente) {
            return false;
        }
        if (!$contrat->exist('versement_fa')) {
            return false;
        }
        if (($contrat->versement_fa == VracClient::VERSEMENT_FA_ANNULATION) || ($contrat->versement_fa == VracClient::VERSEMENT_FA_MODIFICATION) || ($contrat->versement_fa == VracClient::VERSEMENT_FA_NOUVEAU)) {
            return true;
        }
        return false;
    }

}
