<?php

class InterproClient extends acCouchdbClient {
	
	protected static $_base_interpros = array('INTERPRO-CIVP', 'INTERPRO-IR', 'INTERPRO-IVSE');
	protected static $_interpros = array('INTERPRO-IR', 'INTERPRO-CIVP', 'INTERPRO-IVSE', 'INTERPRO-IO', 'INTERPRO-CIVL', 'INTERPRO-ANIVIN');
    const INTERPRO_REFERENTE = 'INTERPRO-IR';
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
    
    public function getAllInterpros() {
    	return self::$_interpros;
    }
    
    public function getInterprosObject() {
    	$interpros = $this->getInterpros();
    	$result = array();
    	foreach ($interpros as $interpro) {
    		$result[] = $this->getById($interpro);
    	}
    	return $result;
    }
    
    public function getInterproReferente()
    {
    	return $this->getById(self::INTERPRO_REFERENTE);
    }
    
    public function getInterproByDepartements($departements = array(), $withReferente = true)
    {
    	$interpros = $this->getInterprosObject();
    	$result = array();
    	foreach ($interpros as $interpro) {
    		$dep = $interpro->departements->toArray();
    		foreach ($departements as $departement) {
    			if (in_array($departement, $dep)) {
    				$result[$interpro->_id] = $interpro;
    			}	
    		}
    	}
    	if (count($result) == 0 && $withReferente) {
    		$result[] = $this->getInterproReferente();
    	}
    	return $result;
    }
    
    public function getInterprosInitialConfiguration() {
    	
    	$civp = new Interpro();
    	$civp->set('_id', 'INTERPRO-CIVP');
    	$civp->identifiant = 'CIVP';
    	$civp->nom = 'CIVP';
    	$civp->email_contrat_vrac = 'eco@provencewines.com';
    	$civp->email_contrat_inscription = 'eco@provencewines.com';
    	
	    $ir = new Interpro();
	    $ir->set('_id', 'INTERPRO-IR');
	    $ir->identifiant = 'IR';
	    $ir->nom = 'InterRhône';
	    $ir->email_contrat_vrac = 'declarvins@inter-rhone.com';
	    $ir->email_contrat_inscription = 'declarvins@inter-rhone.com';
	    
	    $ivse = new Interpro();
	    $ivse->set('_id', 'INTERPRO-IVSE');
	    $ivse->identifiant = 'IVSE';
	    $ivse->nom = "Intervins Sud-Est";
	    $ivse->email_contrat_vrac = '';
	    $ivse->email_contrat_inscription = '';
	    
	    $anivin = new Interpro();
	    $anivin->set('_id', 'INTERPRO-ANIVIN');
	    $anivin->identifiant = 'ANIVIN';
	    $anivin->nom = "Anivin";
	    $anivin->email_contrat_vrac = '';
	    $anivin->email_contrat_inscription = '';
	    
	    $civl = new Interpro();
	    $civl->set('_id', 'INTERPRO-CIVL');
	    $civl->identifiant = 'CIVL';
	    $civl->nom = "CIVL";
	    $civl->email_contrat_vrac = '';
	    $civl->email_contrat_inscription = '';
	    
	    $io = new Interpro();
	    $io->set('_id', 'INTERPRO-IO');
	    $io->identifiant = 'IO';
	    $io->nom = "InterOC";
	    $io->email_contrat_vrac = '';
	    $io->email_contrat_inscription = '';
	    
	    $interpros = array('CIVP' => $civp, 'IR' => $ir, 'IVSE' => $ivse, 'ANIVIN' => $anivin, 'CIVL' => $civl, 'IO' => $io);
	    return $interpros;
    }
    

    
	public function matchInterpro($interpro) {
      if (preg_match('/ir/i', $interpro)) {
        return 'INTERPRO-IR';
      }
	  if (preg_match('/civp/i', $interpro)) {
        return 'INTERPRO-CIVP';
      }
	  if (preg_match('/ivse/i', $interpro)) {
        return 'INTERPRO-IVSE';
      }
	  if (preg_match('/anivin/i', $interpro)) {
        return 'INTERPRO-ANIVIN';
      }
	  if (preg_match('/civl/i', $interpro)) {
        return 'INTERPRO-CIVL';
      }
	  if (preg_match('/io/i', $interpro)) {
        return 'INTERPRO-IO';
      }

      throw new sfException("L'interpro $interpro n'est pas reconnue");
    }

}
