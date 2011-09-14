<?php

class ContratClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Contrat 
     */
    public function retrieveByLogin($login, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('CONTRAT-'.$login, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CONTRAT-00000000000')->endkey('CONTRAT-99999999999')->execute($hydrate);
    }
    

}
