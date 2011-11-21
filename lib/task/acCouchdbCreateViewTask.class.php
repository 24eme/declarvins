<?php

class acCouchdbLoadViewTask extends sfBaseTask {

    protected function configure() {

        $this->addArguments(array(
            new sfCommandArgument('model', sfCommandArgument::REQUIRED, 'Document model'),
            new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'View name'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
            new sfCommandOption('dir', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', sfConfig::get("sf_lib_dir") . "/model/couchdb"),
            new sfCommandOption('id', null, sfCommandOption::PARAMETER_REQUIRED, 'Document id to generate'),
        ));

        $this->namespace = 'couchdb';
        $this->name = 'load-view';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [acCouchdbLoadView|INFO] task does things.
Call it with:

  [php symfony acCouchdbLoadView|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        
    }
}