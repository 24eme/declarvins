<?php

class VracClient extends acCouchdbClient {
    
    /**
     *
     * @return _VracClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Vrac");
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('VRAC-'.$id, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac 
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
        return $this->executeView('vrac', 'all', $hydrate);
    }
    

}
