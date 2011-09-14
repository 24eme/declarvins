<?php

class InterproClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Interpro 
     */
    public function retrieveByLogin($login, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('INTERPRO-'.$login, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('INTERPRO-A')->endkey('INTERPRO-Z')->execute($hydrate);
    }
    

}
