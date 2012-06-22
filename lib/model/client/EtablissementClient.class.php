<?php

class EtablissementClient extends acCouchdbClient {
   
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

    public function retrieveOrCreateById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $etab =  parent::find('ETABLISSEMENT-'.$id, $hydrate);
    	
        if (!$etab) {
    	  	$etab = new Etablissement();
	    	$etab->_id = 'ETABLISSEMENT-'.$id;
    	}

	    return $etab;
    }

  	public function findByInterpro($interpro) {
    	
        return $this->getViewClient("all")->findByInterpro($interpro);
  	}

  	public function findByInterproAndFamilles($interpro, array $familles) {
    	
        return $this->getViewClient("all")->findByInterproAndFamilles($interpro, $familles);
  	}

}
