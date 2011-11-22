<?php

class _CompteClient extends acCouchdbClient {
    
    /**
     *
     * @return _CompteClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("_Compte");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return _Compte 
     */
    public function retrieveByLogin($login, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('COMPTE-'.$login, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return _Compte
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById($id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection 
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('COMPTE-A')->endkey('COMPTE-Z')->execute($hydrate);
    }
}
