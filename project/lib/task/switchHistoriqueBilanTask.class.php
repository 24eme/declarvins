<?php

class switchHistoriqueBilanTask extends sfBaseTask
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

    $this->namespace        = 'bilan';
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
    

	    
	  	if ($bilanFrom = BilanClient::getInstance()->find('BILAN-DRM-'.$arguments['from'])) {
	  		if ($bilanTo = BilanClient::getInstance()->find('BILAN-DRM-'.$arguments['to'])) {
	  			foreach ($bilanFrom->periodes as $key => $values) {
	  				if (!$bilanTo->periodes->exist($key)) {
	  					$bilanTo->periodes->add($key, $values);
	  				}
	  			}
	  			$bilanTo->sortPeriodes();
	  			$bilanTo->save();
	  		} else {
		  		$json = str_replace($arguments['from'], $arguments['to'], json_encode($bilanFrom->toJson()));
		  		try {
		  			BilanClient::getInstance()->storeDoc(json_decode($json));
		  		} catch (Exception $e) {
		  			$this->logSection("bilan", $arguments['to']." déjà existant", null, 'ERROR');
		  		}
	  		}
	  		$bilanFrom->delete();
	  		$this->logSection("bilan", $arguments['from']." bilan switché avec succès", null, 'SUCCESS');
	  		
	  	}
  }
}
