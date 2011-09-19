<?php

class ChaiClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Chai 
     */
    public function retrieveByIdentifiant($identifiant, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('CHAI-'.$identifiant, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CHAI-A')->endkey('CHAI-Z')->execute($hydrate);
    }
    

}
