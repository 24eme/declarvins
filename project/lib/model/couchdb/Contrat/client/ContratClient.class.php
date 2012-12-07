<?php

class ContratClient extends acCouchdbClient {
    
    /**
     *
     * @return _ContratClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Contrat");
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Contrat 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::find('CONTRAT-'.$id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection 
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CONTRAT-00000000000')->endkey('CONTRAT-99999999999')->execute($hydrate);
    }
    
    /**
     *
     * 
     * @return string 
     */
    public function getNextNoContrat() {
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        print_r($contrats);
        if (count($contrats) > 0) {
            return ((double)str_replace('CONTRAT-', '', max($contrats)) + 1);
        } else {
            return $date.'001';
        }
                
    }
    
    /**
     * @param string $date
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection 
     */
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->startkey('CONTRAT-'.$date.'000')->endkey('CONTRAT-'.$date.'999')->execute($hydrate);
    }
    

}
