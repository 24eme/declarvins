<?php

class switchHistoriqueDRMTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('from', sfCommandArgument::REQUIRED, 'from identifiant'),
       new sfCommandArgument('to', sfCommandArgument::REQUIRED, 'to identifiant'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'drm';
    $this->name             = 'switch-history';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $from = EtablissementClient::getInstance()->find($arguments['from']);
    $to = EtablissementClient::getInstance()->find($arguments['to']);
    if ($to) {
	    if (!$from) {
	    	$from = new stdClass();
	    	$from->identifiant = str_replace('ETABLISSEMENT-', '', $arguments['from']);
	    }
	    
	  		$rows = acCouchdbManager::getClient()
	              ->startkey(array($from->identifiant))
	              ->endkey(array($from->identifiant, array()))
	              ->reduce(false)
	              ->getView("drm", "all")
	              ->rows;
	      $i = 0;
	      foreach($rows as $row) {
	      	if ($drm = DRMClient::getInstance()->find($row->id)) {
	      		$drm->setEtablissementInformations($to);
	      		$json = str_replace($from->identifiant, $to->identifiant, json_encode($drm->toJson()));
	      		try {
	      			DRMClient::getInstance()->storeDoc(json_decode($json));
	      		} catch (Exception $e) {
	      			$this->logSection("drm", $row->id." déjà existante", null, 'ERROR');
	      		}
	      		$drm->delete();
	      		$this->logSection("drm", $row->id." drm switchée avec succès", null, 'SUCCESS');
	      		$i++;
	      	}
	      }
	      $this->logSection("drm", $i." drm(s) switchée(s) avec succès", null, 'SUCCESS');
    }
  }
}
