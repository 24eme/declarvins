<?php

class _CompteClient extends acVinCompteClient 
{        
  
  /**
   *
   * @return CurrentClient 
   */
  public static function getInstance() {
      return acCouchdbManager::getClient("_COMPTE");
  }

}
