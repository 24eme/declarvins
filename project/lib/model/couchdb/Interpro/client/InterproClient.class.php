<?php

class InterproClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function retrieveById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('INTERPRO-'.$id, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function getById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById($id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection
     * @todo remplacer la fonction par une vue
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->keys(array('INTERPRO-civp', 'INTERPRO-inter-rhone', 'INTERPRO-intervins-sud-est'))->execute($hydrate);
    }
    

}
