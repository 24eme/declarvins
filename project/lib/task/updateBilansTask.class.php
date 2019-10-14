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
class updateBilansTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
        ));

        $this->namespace = 'update';
        $this->name = 'bilans';
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
        
        
        $bilans = StatistiquesBilanView::getInstance()->findAll()->rows;
        $nb = count($bilans);
        $i=0;
        foreach ($bilans as $b) {
            $bilan = BilanClient::getInstance()->find($b->id);
            $bilan->updateEtablissement();
            $bilan->save();
            $i++;
            echo $bilan->_id." updated - ".round(($i / $nb) * 100);
        }
    }

}
