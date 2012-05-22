<?php

class _CompteClient extends acVinCompteClient 
{        
   
    private $droits = array('administrateur' => 'Administrateur', 'operateur' => 'Opérateur');
   
  /**
   *
   * @return _CompteClient 
   */
  public static function getInstance() {
      return acCouchdbManager::getClient("_COMPTE");
  }
  
  
  public function getDroits()
  {
      return $this->droits;
  }
}
