<?php

class ContratClient extends sfCouchdbClient {
    
    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return sfCouchdbManager::getClient("Contrat");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Contrat 
     */
    public function retrieveById($id, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('CONTRAT-'.$id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAll($hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CONTRAT-00000000000')->endkey('CONTRAT-99999999999')->execute($hydrate);
    }
    
    /**
     *
     * 
     * @return string 
     */
    public function getNextNoContrat() {
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, sfCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($contrats) > 0)
        	return ((int)str_replace('CONTRAT-', '', max($contrats)) + 1);
        else
        	return $date.'001';
    }
    
    /**
     * @param string $date
     * @param integer $hydrate
     * @return sfCouchdbDocumentCollection 
     */
    public function getAtDate($date, $hydrate = sfCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CONTRAT-'.$date.'000')->endkey('CONTRAT-'.$date.'999')->execute($hydrate);
    }
    

}
