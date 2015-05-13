<?php

class DouaneClient extends acCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Douane 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('DOUANE-'.$id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection 
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->executeView('douane', 'all');
    }
    
	public static function getInstance() {
        return acCouchdbManager::getClient("Douane");
    }

}
