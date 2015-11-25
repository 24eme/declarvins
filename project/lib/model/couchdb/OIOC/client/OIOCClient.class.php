<?php

class OIOCClient extends acCouchdbClient {
	
    
    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("OIOC");
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('OIOC-'.$id, $hydrate);
    }
    
    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {
    	return parent::find($this->getId($id_or_identifiant), $hydrate, $force_return_ls);
    }


    public function getId($id_or_identifiant) {
    	$id = $id_or_identifiant;
    	if(strpos($id_or_identifiant, 'OIOC-') === false) {
    		$id = 'OIOC-'.$id_or_identifiant;
    	}
    
    	return $id;
    }

}
