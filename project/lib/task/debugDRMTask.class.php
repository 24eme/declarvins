<?php

class debugDRMTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'debug';
    $this->name             = 'DRM';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
  		$rows = acCouchdbManager::getClient()
              ->reduce(false)
              ->getView("drm", "all")
              ->rows;
      $i = 0;
      $nb = count($rows);
      foreach($rows as $row) {
      	$i++;
      	if ($row->key[1] != '2016-2017') {
			continue;
      	}
      	if ($drm = DRMClient::getInstance()->find($row->id)) {
      		if (!$drm->isValidee()) {
      			continue;
      		}
	      	if ($drm->droits->exist('douane') && $drm->droits->douane->exist('L387_SSIG')) {
	      		$drm->setDroits();
		      	try {
		      		$drm->save(false);
		      		$this->logSection("debug", $drm->_id." droits recalculés avec succès", null, 'SUCCESS');
		      	} catch (Exception $e) {
		      		$this->logSection("debug", $drm->_id." bug", null, 'ERROR');
		      	}
	      	}
      	}
      	$i++;
      	$this->logSection("debug", $i." / ".$nb." (".round(($i / $nb) * 100)."%)", null, 'INFO');
      }
    
  }
}
