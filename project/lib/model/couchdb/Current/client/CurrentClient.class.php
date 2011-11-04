<?php

class CurrentClient extends sfCouchdbClient {
  private static $current = array();
  
  /**
   *
   * @return CurrentClient 
   */
  public static function getInstance() {
      return sfCouchdbManager::getClient("Current");
  }

  /**
   *
   * @return Current 
   */
  public static function getCurrent() {
    if (self::$current == null) {
        self::$current = CacheFunction::cache('model', array(CurrentClient::getInstance(), 'retrieveCurrent'), array());
    }
    return self::$current;
  }
  
  /**
   *
   * @return Current
   */
  public function retrieveCurrent() {
    return parent::retrieveDocumentById('CURRENT');
  }
  
}
