<?php

class annualiseDroitsDrmsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
                // add your own options here
        ));
        $this->addArguments(array(
        		new sfCommandArgument('etablissement', sfCommandArgument::REQUIRED),
        		new sfCommandArgument('periode', sfCommandArgument::REQUIRED)
        ));

        $this->namespace = 'drm';
        $this->name = 'annualise-droits';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony annualise-droits|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
		ini_set('memory_limit', '2048M');
  		set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $etablissement = $arguments['etablissement'];
        $periode = $arguments['periode'];
        $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement, $periode);
        $campagne = null;
        	while ($drm) {
        		if (!$campagne) {
        			$campagne = $drm->campagne;
        		}
        		if ($drm->campagne != $campagne) {
        			$drm = null;
        		}
        		if ($drm) {
	        		$drm->declaratif->paiement->douane->frequence = 'Annuelle';
	        		$drm->setDroits();
			      	try {
			      		$drm->save();
			      		$this->logSection("drm", $drm->_id." annualisée avec succès", null, 'SUCCESS');
			      	} catch (Exception $e) {
			      		$this->logSection("drm", $drm->_id." bug", null, 'ERROR');
			      	}
	        		$periode = DRMClient::getInstance()->getPeriodeSuivante($periode);
	        		$drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($etablissement, $periode);
        		}
        	}
    }

}