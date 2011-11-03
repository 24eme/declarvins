<?php

class EtablissementClient extends sfCouchdbClient {
   
    /**
     *
     * @return EtablissementClient
     */
    public static function getInstance() {
        return sfCouchdbManager::getClient("Etablissement");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Etablissement 
     */
    public function retrieveById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('ETABLISSEMENT-'.$id, $hydrate);
    }
    
    

}
