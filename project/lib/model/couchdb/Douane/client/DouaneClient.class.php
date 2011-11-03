<?php

class DouaneClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Douane 
     */
    public function retrieveById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('DOUANE-'.$id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        //return $this->startkey('DOUANE-A')->endkey('CONTRAT-Z')->execute($hydrate);
        return $this->executeView('douane', 'all');
    }
    

}
