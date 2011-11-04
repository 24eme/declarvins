<?php

class DRMClient extends sfCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
        return sfCouchdbManager::getClient("DRM");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return DRM
     */
    public function retrieveByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('DRM-'.$identifiant.'-'.$campagne, $hydrate);
    }
    
    

}
