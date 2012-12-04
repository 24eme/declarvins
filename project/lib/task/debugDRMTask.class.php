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
      foreach($rows as $row) {
      	$drm = DRMClient::getInstance()->find($row->key[7]);
      	if($drm->declarant->siege->code_postal && $drm->declarant->siege->commune && !is_numeric($drm->declarant->siege->code_postal) && is_numeric($drm->declarant->siege->commune)) {
          $drm->declarant->siege->code_postal = $drm->declarant->siege->commune;
          $drm->declarant->siege->commune = $drm->declarant->siege->adresse;
          $drm->declarant->siege->pays = $drm->declarant->siege->pays;
          $drm->save();
          $this->logSection("debug", $drm->get('_id'), null, 'SUCCESS');
        }
      }
    
  }
}
