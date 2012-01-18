<?php

class DRMClient extends acCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("DRM");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return DRM
     */
    public function retrieveByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
    	return parent::retrieveDocumentById('DRM-'.$identifiant.'-'.$campagne, $hydrate);
    }
    
    
    
    

}
