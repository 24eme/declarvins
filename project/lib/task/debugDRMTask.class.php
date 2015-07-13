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
	if (round(($i / $nb) * 100) < 12) {
		$this->logSection("debug", $row->id." squezzée avec succès", null, 'SUCCESS');
		$i++;
		continue;
	}
      	if ($drm = DRMClient::getInstance()->find($row->id)) {
      		$drm->setDroits();
	      	try {
	      		$drm->save();
	      		$this->logSection("debug", $drm->_id." drm debugguée avec succès", null, 'SUCCESS');
	      	} catch (Exception $e) {
	      		$this->logSection("debug", $drm->_id." bug", null, 'ERROR');
	      	}
      	}
      	$i++;
      	$this->logSection("debug", $i." / ".$nb." (".round(($i / $nb) * 100)."%) drm(s) debugguée(s) avec succès", null, 'SUCCESS');
      }
    
  }
}
