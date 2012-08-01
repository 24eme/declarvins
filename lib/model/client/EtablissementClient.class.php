<?php

class EtablissementClient extends acCouchdbClient {
   
    const FAMILLE_PRODUCTEUR = "producteur";
    const FAMILLE_NEGOCIANT = "negociant";
    const FAMILLE_COURTIER = "courtier";

    /**
     *
     * @return EtablissementClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Etablissement");
    }

    public function getViewClient($view) {
        return acCouchdbManager::getView("etablissement", $view, 'Etablissement');
    }
    
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return Etablissement 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-'.$id, $hydrate);
    }

    public function findByIdentifiant($identifiant, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-'.$identifiant, $hydrate);
    }

    public function retrieveOrCreateById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $etab =  parent::find('ETABLISSEMENT-'.$id, $hydrate);
    	
        if (!$etab) {
    	  	$etab = new Etablissement();
	    	$etab->_id = 'ETABLISSEMENT-'.$id;
    	}

	    return $etab;
    }

    public function findByFamille($famille) {
        
        return $this->startkey(array($famille))
              ->endkey(array($famille, array()))->getView('etablissement', 'all');
    }
    
    public function findAll() 
    {
        
        return $this->limit(100)->getView('etablissement', 'all');
    }

}
