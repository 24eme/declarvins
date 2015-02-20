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
class createBilansDrmTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('etablissement', null, sfCommandOption::PARAMETER_REQUIRED, null)
        ));

        $this->namespace = 'update';
        $this->name = 'createBilansDRM';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $optionEtablissementId = $options['etablissement'];
        if ($etablissement = DRMClient::getInstance()->find($optionEtablissementId)) {
            $this->creatBilanDRMForEtablissement($etablissement);
        } else {
            echo "Etablissement non touvé: $optionEtablissementId\n";
        }
    }

    protected function creatBilanDRMForEtablissement($etablissement) {
        $identifiant = $etablissement->identifiant;
        $firstPeriode = DRMAllView::getInstance()->getFirstDrmPeriodeByEtablissement($identifiant);
        $allPeriodes = $this->createPeriodesFromFirstPeriode($firstPeriode);
        $drm_client = DRMClient::getInstance();
        foreach ($allPeriodes as $periode) {
            $idDrm = $drm_client->buildId($identifiant, $periode);
            $drm = $drm_client->find($idDrm);
            if (!is_null($drm)) {
                $drm = $drm->getMaster();
            }
            $bilan = BilanClient::getInstance()->findOrCreateByIdentifiant($identifiant, 'DRM');
            $bilan->updateEtablissement();
            $currentCampagne = ConfigurationClient::getInstance()->buildCampagne($periode . '-01');
            $firstCampagne = ($currentCampagne == ConfigurationClient::getInstance()->buildCampagne($firstPeriode . '-01'));
            
            $bilan->updateFromDRM($drm, $firstCampagne);
            
            if (is_null($drm)) {
                $bilan->updateDRMManquantesAndNonSaisiesForCampagne($currentCampagne,$periode, $firstCampagne);
            }
            
            $bilan->save();
            echo "Insertion de la période $periode dans le bilan $bilan->_id \n";
        }
    }

    protected function createPeriodesFromFirstPeriode($firstPeriode) {
        $firstCampagne = ConfigurationClient::getInstance()->buildCampagne($firstPeriode . '-01');
        $currentCampagne = ConfigurationClient::getInstance()->buildCampagne(date('Y-m-d'));
        $firstAnnee = (int) substr($firstCampagne, 0, 4);
        $lastAnnee = (int) substr($currentCampagne, 0, 4);
        $periodeArray = array();

        for ($annee = $firstAnnee; $annee <= $lastAnnee; $annee++) {
            $campagne = $annee . "-" . ($annee + 1);
            $periodeForCampagne = ConfigurationClient::getInstance()->getPeriodesForCampagne($campagne);
            if ($campagne == $firstCampagne) {
                foreach ($periodeForCampagne as $periode) {
                    if ($periode >= $firstPeriode) {
                        $periodeArray[] = $periode;
                    }
                }
            } else {
                $periodeArray = array_merge($periodeArray, $periodeForCampagne);
            }
        }
        return $periodeArray;
    }

}
