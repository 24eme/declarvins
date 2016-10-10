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
        $vracs = acCouchdbManager::getClient()
              ->reduce(false)
              ->getView("vrac", "all")
              ->rows;
        $i = 1;
        $nb = count($vracs);
        foreach ($vracs as $v) {
        	$vrac = VracClient::getInstance()->find($v->id);
        	$remove = array();
        	foreach ($vrac->getOrAdd('enlevements') as $k => $v) {
        		$drm = DRMClient::getInstance()->find("DRM-T0001-2016-09");
        		if (!$drm) {
        			$remove[] = $k;
        		}
        	}
        	if ($remove) {
        		foreach ($remove as $r) {
        			$vrac->enlevements->remove($r);
        		}
        		$vrac->save();
        		$this->logSection("debug", $vrac->_id." debuggué avec succès ($i / $nb)", null, 'SUCCESS');
        	} else {
        		$this->logSection("squeeze", "($i / $nb)", null, 'INFO');
        	}
			$i++;
        }
    }

}