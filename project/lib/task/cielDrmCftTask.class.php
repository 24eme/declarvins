<?php

class cielDrmCft extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('targetfolder', sfCommandArgument::REQUIRED, 'Dossier contenant les DRM en retour de CIEL'),
  	));
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'drm-cft';
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
    
    if (file_exists($arguments['targetfolder'])) {
    	$files = scandir($arguments['targetfolder']);
    	foreach ($files as $file) {
    		if (is_file($arguments['targetfolder'].'/'.$file)) {
    			$content = file_get_contents($arguments['targetfolder'].'/'.$file);
    			$xmlIn = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    			$ea = (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
				$periode = sprintf("%4d-%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
    			if ($drm = CielDrmView::getInstance()->findByAccisesPeriode($ea, $periode)) {
    				$export = new DRMExportCsvEdi($drm);
    				if ($xml = $export->exportEDI('xml', $contextInstance)) {
    					$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    					$compare = new DRMCielCompare($xmlIn, $xmlOut);
    					var_dump($compare->getDiff());exit;
    				}
    			}
    			
    		}
    	}
    }
  }
}
