<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'configuration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importConfiguration|INFO] task does things.
Call it with:

  [php symfony importConfiguration|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $import_dir = sfConfig::get('sf_data_dir').'/import/configuration';
    
    $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION', acCouchdbClient::HYDRATE_JSON);
    if ($configuration) {
      acCouchdbManager::getClient()->deleteDoc($configuration);
    }
	    
    $configuration = new Configuration();
    
    $interpros = InterproClient::getInstance()->getInterprosInitialConfiguration();
  	foreach ($interpros as $id => $interpro) {
  		if ($inter = acCouchdbManager::getClient()->retrieveDocumentById($interpro->_id)) {
	        $inter->delete();
	    }
	    if ($configurationProduits = acCouchdbManager::getClient()->retrieveDocumentById(ConfigurationProduitClient::getInstance()->buildId($interpro->_id))) {
	    	$configurationProduits->delete();
	    }
  		$configurationFile = $import_dir.'/catalogue_produits_'.$id.'.csv';
  		if (file_exists($configurationFile)) {
	  		$configurationProduits = ConfigurationProduitClient::getInstance()->getOrCreate($interpro->_id);
  			$csv = new ConfigurationProduitCsvFile($configurationProduits, $configurationFile);
  			$configurationProduits = $csv->importProduits();
  			if ($csv->hasErrors()) {
				throw new sfException(implode("\n", $csv->getErrors()));
			}
			$configurationProduits->interpro_object = $interpro;
			$configurationProduits->save();
			$interpro->configuration_produits = $configurationProduits->_id;
			$interpro->save();
			$produits_interpro = $configuration->produits->add($interpro->_id, $configurationProduits->_id);
  		} else {
  			$this->logSection('configuration', 'Aucun CSV produits pour l\'interpro : '.$id, null, 'ERROR');
  		}
  	}
  	
    $this->logSection('configuration', 'produits importés');
    
    $zones = ConfigurationZoneClient::getInstance()->getZonesInitialConfiguration();
    $zonesIds = array();
    
    foreach ($zones as $zone) {
  		if ($z = acCouchdbManager::getClient()->retrieveDocumentById($zone->_id)) {
	        $z->delete();
	    }
    	$zone->save();
    	foreach ($zone->liaisons as $interproId) {
    		if ($interpro = acCouchdbManager::getClient()->retrieveDocumentById($interproId)) {
    			$interpro->zone = $zone->_id;
    			$interpro->save();
    		}
    	}
    	$zonesIds[] = $zone->_id;
    }
    $configuration->zones = $zonesIds;
    
    $this->logSection('configuration', 'zones importés');
    
    $csv = new VracConfigurationCsvFile($configuration, $import_dir.'/vrac.csv');
    $configuration = $csv->importConfigurationVrac();
    
    $this->logSection('configuration', 'configuration vrac importée');
    
    $csv = new DAIDSConfigurationCsvFile($configuration, $import_dir.'/daids.csv');
    $configuration = $csv->importConfigurationDAIDS();
    
    $this->logSection('configuration', 'configuration daids importée');

  	$configuration->save();
  }
}