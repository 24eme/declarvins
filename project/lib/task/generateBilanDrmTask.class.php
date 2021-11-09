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

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
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

        $campagne = (new CampagneManager('08'))->getCurrent();
        $periodes = $this->getPeriodes($campagne);

        $interpro = InterproClient::getInstance()->retrieveById('CIVP');
        $zoneId = $interpro->zone;

        $etablissements = array_merge(
            EtablissementAllView::getInstance()->findByZoneAndFamille($zoneId, EtablissementFamilles::FAMILLE_PRODUCTEUR)->rows,
            EtablissementAllView::getInstance()->findByZoneAndSousFamille($zoneId, EtablissementFamilles::FAMILLE_PRODUCTEUR, EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR)->rows
        );

        foreach($etablissements as $etablissement) {
            $historique = new DRMHistorique($etablissement->key[EtablissementAllView::KEY_IDENTIFIANT]);

            echo $etablissement->id;
            echo "\n";

            $drms = $historique->getDRMsByCampagne($campagne, true);
            $lastDrm = $historique->getLastDRM();

            foreach($periodes as $periode) {
                if ($lastDrm && $lastDrm->periode < $periode && $lastDrm->hasStocksEpuise()) {
                    $statut = DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE;
                } elseif (isset($drms[$periode])) {
                    $statut = DRMClient::getInstance()->find($drms[$periode]->_id)->getStatutBilan();
                } else {
                    $statut = DRMClient::DRM_STATUS_BILAN_A_SAISIR;
                }
                echo "\t$periode : $statut\n";
            }
        }
    }

    public function getPeriodes($campagne) {
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
