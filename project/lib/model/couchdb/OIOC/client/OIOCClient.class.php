<?php

class OIOCClient extends acCouchdbClient {
	
    
    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("OIOC");
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('OIOC-'.$id, $hydrate);
    }

}
