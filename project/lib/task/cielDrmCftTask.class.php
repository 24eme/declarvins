<?php

class cielDrmCft extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('xmlfolder', sfCommandArgument::REQUIRED, 'Dossier contenant les DRM en retour de CIEL'),
      new sfCommandArgument('archivesfolder', sfCommandArgument::REQUIRED, 'Dossier contenant les DRM en retour de CIEL'),
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
    
    if (file_exists($arguments['xmlfolder'])) {
    	$files = scandir($arguments['xmlfolder']);
    	foreach ($files as $file) {
    		if (is_file($arguments['xmlfolder'].'/'.$file)) {
    			$content = file_get_contents($arguments['xmlfolder'].'/'.$file);
    			$xmlIn = simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    			$ea = (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
				$periode = sprintf("%4d-%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
    			if ($drm = CielDrmView::getInstance()->findByAccisesPeriode($ea, $periode)) {
    				$export = new DRMExportCsvEdi($drm);
    				if ($xml = $export->exportEDI('xml', $contextInstance)) {
    					$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    					$compare = new DRMCielCompare($xmlIn, $xmlOut);
    					if ($compare->hasDiff()) {
    						$drm_rectificative = $drm->generateRectificative();
    						$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
    						$drm_rectificative->add('ciel', $drm->ciel);
    						$drm_rectificative->ciel->xml = null;
    						$drm_rectificative->save();
    						$this->logSection("rectificative", $drm->_id." nécessite une correction du déclarant", null, 'ERROR');
    					} else {
    						$drm->ciel->valide = 1;
    						$drm->save();
    						$this->logSection("drm", $drm->_id." drm validée sur CIEL avec succès", null, 'SUCCESS');
    					}
    					$archive = rename ($arguments['xmlfolder'].'/'.$file, $arguments['archivesfolder'].'/'.$file);
    					if (!$archive) {
    						$this->logSection("archivage", $arguments['xmlfolder'].'/'.$file." non archivé", null, 'ERROR');
    					}
    				}
    			}
    			
    		}
    	}
    }
  }
}
