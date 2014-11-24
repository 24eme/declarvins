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
class updateDrmsWithMouvementsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('drm', null, sfCommandOption::PARAMETER_REQUIRED, null)
        ));

        $this->namespace = 'update';
        $this->name = 'drmsWithMouvements';
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
        $optionDrmId = $options['drm'];
        if ($drm = DRMClient::getInstance()->find($optionDrmId)) {
            echo "Mouvements crées pour $optionDrmId\n";
            $drm->validate(array('onlyUpdateMouvements'));
            $drm->save();
        }else{
            echo "Pas de drm touvée pour $optionDrmId\n";
        }
    }

}
