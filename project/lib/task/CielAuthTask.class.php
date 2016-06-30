<?php

class CielAuthTask extends sfBaseTask
{
	public static $supported_algs = array(
			'HS256' => array('hash_hmac', 'SHA256'),
			'HS512' => array('hash_hmac', 'SHA512'),
			'HS384' => array('hash_hmac', 'SHA384'),
			'RS256' => array('openssl', 'SHA256'),
	);
	
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
    $this->name             = 'oauth';
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
  		$oauth = new CielService();
  		$token = $oauth->transfer($this->getXml());
  		echo $token;
  		
  	} catch (sfException $e) {
  		echo "/!\ Erreurs :\n";
  		echo $e->getMessage();
  		echo "\n";
  	}
    
  }
  
  protected function getXml()
  {
  	$xml = '<?xml version="1.0" encoding="utf-8" ?>
<message-interprofession xmlns="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://douane.finances.gouv.fr/app/ciel/interprofession/echanges/1.0 echanges-interprofession-1.7.xsd">
</message-interprofession>';
  	return $xml;
  }
}
