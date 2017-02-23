<?php

class CielCftCompareTask extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('xmlPlateforme', sfCommandArgument::REQUIRED, 'DRM DeclarVins'),
      new sfCommandArgument('xmlCiel', sfCommandArgument::REQUIRED, 'DRM CIEL'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'cft-compare';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    

    $contextInstance = sfContext::createInstance($this->configuration);
    
    
    $xmlIn = simplexml_load_file($arguments['xmlPlateforme'], 'SimpleXMLElement');
    $xmlOut = simplexml_load_file($arguments['xmlCiel'], 'SimpleXMLElement');

	$compare = new DRMCielCompare($xmlIn, $xmlOut);
    if ($compare->hasDiff()) {
    	$diffs= '';
    	foreach ($compare->getLitteralDiff()as $k => $v) {
    		$diffs .= "$k : $v\n";
    	}
    	echo $diffs;
    }

  }
}
