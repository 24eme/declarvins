<?php

class VracClient extends acCouchdbClient {
    
    /**
     *
     * @return _VracClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("Vrac");
    }

    public function retrieveByNumeroAndEtablissementAndHashOrCreateIt($id, $etablissement, $hash, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $vrac = $this->retrieveById($id);
      if (!$vrac) {
	$vrac = new Vrac();
	$vrac->etablissement = $etablissement;
	$vrac->numero = $id;
	$vrac->produit = $hash;
      }
      if ($etablissement != $vrac->etablissement)
	throw new sfException('Etablissement ne correpond pas à l\'établissement initial');
      if (preg_match("|^$hash|", $vrac->produit))
	throw new sfException('Le hash du produit ne correpond pas au hash initial');
      return $vrac;
    }
    
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac 
     */
    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('VRAC-'.$id, $hydrate);
    }
    
    /**
     *
     * @param string $id
     * @param integer $hydrate
     * @return Vrac 
     */
    public function getById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById($id, $hydrate);
    }
    
    /**
     *
     * @param integer $hydrate
     * @return acCouchdbDocumentCollection
     */
    public function getAll($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->executeView('vrac', 'all', $hydrate);
    }
    

    public function retrieveFromEtablissementsAndHash($etablissement, $hash, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $contrats = array();
      foreach ($this->retrieveFromEtablissements($etablissement) as $contrat) {
	if (strpos($hash, $contrat->produit) !== false)
	  $contrats[] = $contrat;
      }
      return $contrats;
    }

    public function retrieveFromEtablissements($etablissement, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $contrats = array();
      foreach ($this->startkey(array($etablissement))
	       ->endkey(array($etablissement, array()))->getView('vrac', 'all', $hydrate)->rows as $c) {
	$contrats[] = $this->retrieveById($c->key[1]);
      }
      return $contrats;
    }
    

}
