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
  
}
