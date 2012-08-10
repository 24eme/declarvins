<?php

class VracClient extends acCouchdbClient {
   
	/*
	 * @todo Rendre generique les constantes de transaction
	 */
    const TYPE_TRANSACTION_RAISINS = 'raisins';
    const TYPE_TRANSACTION_MOUTS = 'mouts';
    const TYPE_TRANSACTION_VIN_VRAC = 'vin_vrac';
    const TYPE_TRANSACTION_VIN_BOUTEILLE = 'vin_bouteille';
    
    
    const STATUS_CONTRAT_SOLDE = 'SOLDE';
    const STATUS_CONTRAT_ANNULE = 'ANNULE';
    const STATUS_CONTRAT_NONSOLDE = 'NONSOLDE';
    

    /**
     *
     * @return DRMClient
     */
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Vrac");
    }

    public function getId($numeroContrat)
    {
      return 'VRAC-'.$numeroContrat;
    }

    public function getNextNoContrat()
    {   
        $id = '';
    	$date = date('Ymd');
    	$contrats = self::getAtDate($date, acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
        if (count($contrats) > 0) {
            $id .= ((double)str_replace('VRAC-', '', max($contrats)) + 1);
        } else {
            $id.= $date.'001';
        }

        return $id;
    }
    
    public function getAtDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
        return $this->startkey('VRAC-'.$date.'000')->endkey('VRAC-'.$date.'999')->execute($hydrate);        
    }
    
    public function findByNumContrat($num_contrat) 
    {
      return $this->find($this->getId($num_contrat));
    }
    
    public function retrieveLastDocs() 
    {
      return $this->descending(true)->limit(300)->getView('vrac', 'history');
    }
    
    public function retrieveBySoussigne($soussigneParam) 
    {
      return $this->startkey(array($soussigneParam))
              ->endkey(array($soussigneParam, array()))->limit(300)->getView('vrac', 'soussigneidentifiant');
    }
    /*
     * Methodes de l'ancien plugin
     */

    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('VRAC-'.$id, $hydrate);
    }
    

    public function retrieveFromEtablissementsAndHash($etablissement, $hash, $mustActive = true, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $contrats = array();
      $hash = preg_replace('|(couleurs/[^/]*/).*|', '\1', $hash);
      foreach ($this->startkey(array($etablissement))
	       ->endkey(array($etablissement, array()))->getView('vrac', 'all', $hydrate)->rows as $c) {
	       	if (strpos($hash, $c->key[1]) !== false) {
      	if ($mustActive && $c->key[3] == Configuration::STATUT_CONTRAT_NONSOLDE) {
      		$contrats[] = parent::retrieveDocumentById($c->key[2]);
      	}
	       	}
      }
      return $contrats;
    }

    public function retrieveFromEtablissements($etablissement, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $contrats = array();
      foreach ($this->startkey(array($etablissement))
	       ->endkey(array($etablissement, array()))->getView('vrac', 'all', $hydrate)->rows as $c) {
	$contrats[] = parent::retrieveDocumentById($c->key[2]);
      }
      return $contrats;
    }
    
    public function retrieveByNumeroAndEtablissementAndHashOrCreateIt($id, $etablissement, $hash, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $vrac = $this->retrieveById($id);
      if (!$vrac) {
	$vrac = new Vrac();
	$vrac->vendeur_identifiant = "ETABLISSEMENT-".$etablissement;
	$vrac->numero_contrat = $id;
	$vrac->produit = $hash;
      }
      if ($etablissement != str_replace('ETABLISSEMENT-', '', $vrac->vendeur_identifiant))
	throw new sfException('le vendeur ne correpond pas à l\'établissement initial');
      if (!preg_match("|^$hash|", $vrac->produit))
	throw new sfException('Le hash du produit ne correpond pas au hash initial ('.$vrac->produit.'<->'.$hash.')');
      return $vrac;
    }
 }
