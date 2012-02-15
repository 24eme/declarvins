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

  public function findProduits() {
    return $this->startkey(array("produits"))
              ->endkey(array("produits", array()))->getView('configuration', 'produits');
  }

  public function findProduitsByCertification($certif, $interpro, $departement = null) {

    return $this->startkey(array("produits", $certif, $interpro))
              ->endkey(array("produits", $certif, $interpro, array()))->getView('configuration', 'produits');
  }

  public function findProduitsByAppellation($certif, $interpro, $departement, $appellation) {

    return $this->startkey(array("produits", $certif, $interpro, $departement, $appellation))
              ->endkey(array("produits", $certif, $interpro, $departement, $appellation, array()))->getView('configuration', 'produits');
  }
  
}
