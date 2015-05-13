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

    public function find($id_or_identifiant, $hydrate = self::HYDRATE_DOCUMENT, $force_return_ls = false) {
        return parent::find($this->getId($id_or_identifiant), $hydrate, $force_return_ls);
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

    public function matchFamille($f) {
      if (preg_match('/producteur/i', $f)) {
        
        return EtablissementFamilles::FAMILLE_PRODUCTEUR;
      }
      if (preg_match('/n.{1}gociant/i', $f)) {
        
        return EtablissementFamilles::FAMILLE_NEGOCIANT;
      }
      if (preg_match('/courtier/i', $f)) {
        
        return EtablissementFamilles::FAMILLE_COURTIER;
      }

      throw new sfException("La famille $f doit être soit producteur soit negociant soit courtier");
    }

    public function matchSousFamille($sf) {
      $sf = KeyInflector::slugify($sf);
      $matches = array("cave.{1}particuli.{1}re" => EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE,
      					 "cave.{1}coop.{1}rative" => EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE,
                         "r.{1}gional" => EtablissementFamilles::SOUS_FAMILLE_REGIONAL,
                         "ext.{1}rieur" => EtablissementFamilles::SOUS_FAMILLE_EXTERIEUR,
                         ".{1}tranger" =>  EtablissementFamilles::SOUS_FAMILLE_ETRANGER,
                         "union" => EtablissementFamilles::SOUS_FAMILLE_UNION,
                         "vinificateur" => EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR);
      foreach ($matches as $match => $s) {
        if (preg_match('/'.$match.'/i', $sf)) {
          return $s;
        }
      }

      if (!$sf) {
        return null;
      }

      throw new sfException('Sous Famille "'.$sf.'" inconnue');
    }

    public function getEtablissementsByContrat($contrat) {
    	$etablissements = array();
    	$result = EtablissementAllView::getInstance()->findByContrat($contrat);
    	foreach ($result as $etab) {
    		$etablissements[$etab->id] = $this->find($etab->id);
    	}
    	return $etablissements;
    }

}
