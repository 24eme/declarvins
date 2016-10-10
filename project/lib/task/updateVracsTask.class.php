<?php

class updateVracsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
	      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
	      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
	      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
	      // add your own options here
	    ));
        $this->namespace = 'update';
        $this->name = 'vracs';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
    	$databaseManager = new sfDatabaseManager($this->configuration);
    	$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $drms = acCouchdbManager::getClient()
        		->reduce(false)
              ->getView("drm", "all")
              ->rows;
        $i = 1;
        $nb = count($drms);
        foreach ($drms as $d) {
			$drm = DRMClient::getInstance()->find($d->id);
			if ($drm->isValidee()) {
				$drm->updateVrac();
				$this->logSection("update", $drm->_id." mis à jour avec succès ($i / $nb)", null, 'SUCCESS');
			} else {
				$this->logSection("update", "($i / $nb)", null, 'INFO');
			}
			$i++;
        }
    }

}