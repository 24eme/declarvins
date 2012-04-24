<?php

class EtablissementClient extends acCouchdbClient {
   
    /**
     *
     * @return EtablissementClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Etablissement 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('ETABLISSEMENT-'.$id, $hydrate);
    }

  	public function findByInterpro($interpro) {
    	return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('etablissement', 'all');
  	}
    
    

}
