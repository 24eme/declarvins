<?php

class EtablissementClient extends sfCouchdbClient {
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Douane 
     */
    public function retrieveById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('ETABLISSEMENT-'.$id, $hydrate);
    }

}
