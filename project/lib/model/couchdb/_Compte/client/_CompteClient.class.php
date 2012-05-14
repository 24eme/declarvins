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
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return _Compte
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
        return parent::retrieveDocumentById($id, $hydrate);
    }
}
