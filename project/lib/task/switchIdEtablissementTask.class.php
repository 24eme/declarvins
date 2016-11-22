<?php

class switchIdEtablissementTask extends sfBaseTask
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

    $this->namespace        = 'etablissement';
    $this->name             = 'switch-id';
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
    $to = $arguments['to'];
    
    if ($from && $to) {
    	$deleteId = $from->identifiant;
    	$new = clone $from;
    	$new->identifiant = str_replace('ETABLISSEMENT-', '', $to);
    	$new->constructId();
    	foreach ($new->correspondances as $interpro => $correspondance) {
    		if ($correspondance == $deleteId) {
    			$new->correspondances->set($interpro, $new->identifiant);
    		}
    	}
    	if ($compte = $from->getCompteObject()) {
    		if ($compte->tiers->exist($from->_id)) {
    			$compte->tiers->remove($from->_id);
    		}
    		$compte->addTiers($new);
    		$compte->save();
    	}
    	$interpros = InterproClient::getInstance()->getInterprosObject();
    	foreach ($interpros as $interpro) {
    		$remove = array();
    		foreach ($interpro->correspondances as $idBase => $idCorrespondance) {
    			if ($from->identifiant == $idCorrespondance) {
    				$interpro->correspondances->set($idBase, $new->identifiant);
    			}
    			if ($from->identifiant == $idBase) {
    				$remove[] = $idBase;
    				$interpro->correspondances->add($new->identifiant, $idCorrespondance);
    			}
    		}
    		foreach ($remove as $r) {
    			$interpro->correspondances->remove($r);
    		}
    		$interpro->save();
    	}
    	$new->save();
    	$from->delete();
    	$this->logSection("etablissement", $deleteId." switché en ".$new->identifiant." avec succès", null, 'SUCCESS');
    }
    $this->logSection("etablissement", $arguments['from']." non trouve", null, 'ERROR');
    
  }
}
