<?php

class _CompteClient extends sfCouchdbClient {
    
    /**
     *
     * @return _CompteClient
     */
    public static function getInstance() {
        return sfCouchdbManager::getClient("_Compte");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return _Compte 
     */
    public function retrieveByLogin($login, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('COMPTE-'.$login, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return _Compte 
     */
    public function getById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById($id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('COMPTE-A')->endkey('COMPTE-Z')->execute($hydrate);
    }
}
