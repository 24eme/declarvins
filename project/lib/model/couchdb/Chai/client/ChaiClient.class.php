<?php

class ChaiClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Chai 
     */
    public function retrieveByLogin($login, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('CHAI-'.$login, $hydrate);
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
