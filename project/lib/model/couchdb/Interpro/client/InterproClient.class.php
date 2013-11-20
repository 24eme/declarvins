<?php

class InterproClient extends acCouchdbClient {
	
	protected static $_base_interpros = array('INTERPRO-CIVP', 'INTERPRO-IR', 'INTERPRO-IVSE');
    
    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Interpro");
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('INTERPRO-'.$id, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Interpro 
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById($id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection
     * @todo remplacer la fonction par une vue
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->keys($this->getInterpros())->execute($hydrate);
    }
    
    public function getInterpros() {
    	return self::$_base_interpros;
    }
    
    public function getInterprosObject() {
    	$interpros = $this->getInterpros();
    	$result = array();
    	foreach ($interpros as $interpro) {
    		$result[] = $this->getById($interpro);
    	}
    	return $result;
    }

}
