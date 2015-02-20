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
class updateBilansForCurrentCampagneDrmTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('etablissement', null, sfCommandOption::PARAMETER_REQUIRED, null)
        ));

        $this->namespace = 'update';
        $this->name = 'updateBilansForCurrentCampagneDrmTask';
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
            $this->updateBilansForCurrentCampagneDrm($etablissement);
        } else {
            echo "Etablissement non touvé: $optionEtablissementId\n";
        }
    }

    protected function updateBilansForCurrentCampagneDrm($etablissement) {
        $identifiant = $etablissement->identifiant;
        $currentCampagne = ConfigurationClient::getInstance()->buildCampagne(date('Y-m-d'));
        $periodes = ConfigurationClient::getInstance()->getPeriodesForCampagne($currentCampagne);
        $bilan = BilanClient::getInstance()->findOrCreateByIdentifiant($identifiant, 'DRM');
        foreach ($periodes as $periode) {
            $bilan->updateDRMManquantesAndNonSaisiesForCampagne($currentCampagne, $periode);
        }
        $bilan->save();
        echo "BILAN $bilan->_id updated \n";
    }

}
