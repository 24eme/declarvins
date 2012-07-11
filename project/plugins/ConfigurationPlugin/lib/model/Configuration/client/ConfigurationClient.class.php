<?php

class ConfigurationClient extends acCouchdbClient {
	
	private static $current = array();

	/**
	*
	* @return CurrentClient 
	*/
	public static function getInstance() {

	  	return acCouchdbManager::getClient("CONFIGURATION");
	}

	/**
	*
	* @return Current 
	*/
	public static function getCurrent() {
		if (self::$current == null) {
		  self::$current = CacheFunction::cache('model', array(ConfigurationClient::getInstance(), 'retrieveCurrent'), array());
		}

		return self::$current;
	}
  
	/**
	*
	* @return Current
	*/
	public function retrieveCurrent() {
	  	$configuration = parent::retrieveDocumentById('CONFIGURATION');
	  	if (!sfConfig::get('sf_debug')) {
	    	$configuration->loadAllData();
	  	}

		return $configuration;
	}
  
  public function findProduitsForAdmin($interpro) {
    return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('configuration', 'produits_admin');
  }
  
  public function findProduitsByCertificationAndInterpro($interpro, $certif) {
    return $this->startkey(array($interpro, $certif))
              ->endkey(array($interpro, $certif, array()))->getView('configuration', 'produits_admin');
  }
  
}
