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
              ->getView("drm", "debug")
              ->rows;
      $i = 0;
      foreach($rows as $row) {
      	$drm = DRMClient::getInstance()->find($row->key[0]);
      	if ($drm) {
	        $drm->identifiant_drm_historique = null;
	        $drm->identifiant_ivse = null;
	        $drm->save();
	        $this->logSection("debug", $drm->get('_id'), null, 'SUCCESS');
	        $i++;
      	}
      }
      $this->logSection("debug", $i." drm(s) debugguées avec succès", null, 'SUCCESS');
    
  }
}
