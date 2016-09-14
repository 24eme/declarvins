<?php

class switchHistoriqueVracTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument('from', sfCommandArgument::REQUIRED, 'from identifiant'),
       new sfCommandArgument('to', sfCommandArgument::REQUIRED, 'to identifiant'),
     ));

    $this->addOptions(array(
      new sfCommandOption('archivage', null, sfCommandOption::PARAMETER_OPTIONAL, 'Archivage de l\'etablissement', '0'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'vrac';
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
    $hasFrom = true;
    $to = EtablissementClient::getInstance()->find($arguments['to']);
    $archivage = $options['archivage'];

    if ($to) {
	    if (!$from) {
	    	$hasFrom = false;
	    	$from = new stdClass();
	    	$from->identifiant = str_replace('ETABLISSEMENT-', '', $arguments['from']);
	    	$from->famille = $to->famille;
	    }
	    
	    if ($from->famille == "producteur" || $to->famille == "producteur") {
	    	$this->logSection("vrac", $from->identifiant." ".$from->famille." / ".$to->identifiant." ".$to->famille, null, 'ERROR');
	    } else {
	  		$rows = acCouchdbManager::getClient()
	              ->startkey(array($from->identifiant))
	              ->endkey(array($from->identifiant, array()))
	              //->reduce(false)
	              ->getView("vrac", "etablissement")
	              ->rows;
	      	$i = 0;
	      	foreach($rows as $row) {
		      		if ($vrac = VracClient::getInstance()->find($row->id)) {
		      		$type = $vrac->getTypeByEtablissement($from->identifiant);
		      		$vrac->storeSoussigneInformations($type, $to);
		      		$vrac->{$type.'_identifiant'} = $to->identifiant;
		      		$vrac->save(false);
		      		$this->logSection("vrac", $vrac->_id." contrat switché avec succès", null, 'SUCCESS');
		      		$i++;
	      		}
	      	}
	      	if ($archivage && $hasFrom) {
	      		$from->statut = Etablissement::STATUT_ARCHIVE;
	      		$from->save();
	      		$this->logSection("vrac", "etablissement ".$from->identifiant." archivé avec succès", null, 'SUCCESS');
	      	}
	      	$this->logSection("vrac", $i." contrat(s) switché(s) avec succès", null, 'SUCCESS');
	    }
    }

  }
}
