<?php

class CielSeedTask extends sfBaseTask
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

    $this->namespace        = 'ciel';
    $this->name             = 'seed';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
  	try {
  		$service = new CielService("IR");
  		$result = $service->seed($this->getXml());
  		var_dump($result);exit;
  		echo (strpos($result, '<traderAuthorisation>') !== false)? 'valide' : 'non valide';
  		
  	} catch (sfException $e) {
  		echo "/!\ Erreurs :\n";
  		echo $e->getMessage();
  		echo "\n";
  	}
    
  }
  
  protected function getXml()
  {
  	return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.seed.douane.finances.gouv.fr/">
<soapenv:Header/>
<soapenv:Body>
<ws:getInformation>
<numAccises>
<numAccise>FR093027E0635</numAccise>
</numAccises>
</ws:getInformation>
</soapenv:Body>
</soapenv:Envelope>
  	';
  }
}
