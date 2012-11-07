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
     * @deprecated find()
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return parent::find('ETABLISSEMENT-'.$id, $hydrate);
    }

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT) {
        return parent::find($this->getId($id_or_identifiant), $hydrate);
    }
    
    public function findByCvi($cvi) {
        $rows = EtablissementFindByCviView::getInstance()->findByCvi($cvi);
        
        if(!count($rows)) {
            return null;
        }
        
        return $this->find($rows[0]->id);
    }

    public function getId($id_or_identifiant) {
        $id = $id_or_identifiant;
        if(strpos($id_or_identifiant, 'ETABLISSEMENT-') === false) {
            $id = 'ETABLISSEMENT-'.$id_or_identifiant;
        }

        return $id;
    }

    public function getIdentifiant($id_or_identifiant) {

        return $identifiant = str_replace('ETABLISSEMENT-', '', $id_or_identifiant);
    }

    /**
     * 
     * @deprecated find()
     */
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

    public function findByFamille($famille,$limit=100) {
        if($limit==null){            
            return $this->startkey(array($famille))
              ->endkey(array($famille, array()))->getView('etablissement', 'tous');
        }
        return $this->startkey(array($famille))
              ->endkey(array($famille, array()))->limit($limit)->getView('etablissement', 'tous');
    }
    
    public function findAll() 
    {
        
        return $this->limit(100)->getView('etablissement', 'tous');
    }

    public function matchSousFamille($sf) {
      $sf = KeyInflector::slugify($sf);
      $matches = array("(particuliere|cooperative)" => EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE,
                         "regional" => EtablissementFamilles::SOUS_FAMILLE_REGIONAL,
                         "exterieur" => EtablissementFamilles::SOUS_FAMILLE_EXTERIEUR,
                         "etranger" =>  EtablissementFamilles::SOUS_FAMILLE_ETRANGER,
                         "union" => EtablissementFamilles::SOUS_FAMILLE_UNION,
                         "vinificateur" => EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR);
      foreach ($matches as $match => $s) {
        if (preg_match('/'.$match.'/i', $sf)) {
          return $this->_set('sous_famille', $s);
        }
      }

      if (!$sf) {
        return $this->_set('sous_famille', EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE);
      }

      throw new sfException('Sous Famille "'.$sf.'" inconnue');
    }

}
