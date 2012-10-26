<?php

class alertesDrmsManquantesTask extends sfBaseTask {

    protected function configure() {
        $this->addArguments(array(
            new sfCommandArgument('campagne', sfCommandArgument::OPTIONAL, 'Campagne au format AAAA-AAAA'),
        ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'alertes';
        $this->name = 'drms-manquantes';
        $this->briefDescription = 'Retourne les drms considérées comme manquantes';
        $this->detailedDescription = <<<EOF
The [alertes|INFO] task does things.
Call it with:
  Retourne les drms considérées comme manquantes
  [php symfony alertes|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        ini_set('memory_limit', '512M');
        set_time_limit('3600');
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        if (isset($arguments['campagne']) && !empty($arguments['campagne'])) {
        	$campagne = $arguments['campagne'];
        }
		$drmsManquantes = new DRMsManquantes();
		$alertes = $drmsManquantes->getAlertes($campagne);
		foreach ($alertes as $alerte) {
			$alerte->save();
			$this->logSection('drm_manquante', $alerte->_id.' was created');
		}
    }

}