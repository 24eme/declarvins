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
      $numero = 1;
    	$date = date('Ymd');
    	$vrac = $this->findLastByDate($date, acCouchdbClient::HYDRATE_JSON);
      if ($vrac) {
        $numero += (int) str_replace($date, '', $vrac->numero_contrat);
      }

      return sprintf('%s%03d', $date, $numero);
    }
    
    public function findLastByDate($date, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
        $vracs = $this->startkey('VRAC-'.$date.'000')->endkey('VRAC-'.$date.'999')->execute($hydrate)->getDocs();
        krsort($vracs);

        foreach($vracs as $vrac) {
          
          return $vrac;
        }        

        return null;
    }
    
    public function findByNumContrat($num_contrat) 
    {
      return $this->find($this->getId($num_contrat));
    }
    
    public function retrieveFromEtablissementsAndHash($etablissement, $hash, $mustActive = true, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $contrats = array();
        $hash = preg_replace('|(couleurs/[^/]*/).*|', '\1', $hash);
        $vracs = VracAllView::getInstance()->findByEtablissement($etablissement);
        foreach ($vracs->rows as $c) {
            if (strpos('/'.$c->key[VracAllView::VRAC_VIEW_PRODUIT], $hash) === false) {
                continue;
            }
            if ($mustActive && $c->key[VracAllView::VRAC_VIEW_STATUT] == Configuration::STATUT_CONTRAT_NONSOLDE) {
               $contrats[] = parent::retrieveDocumentById($c->key[VracAllView::VRAC_VIEW_ID]);
           }
            
      }
      return $contrats;
    }

    public function retrieveFromEtablissements($etablissement, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $contrats = array();
      foreach ($this->startkey(array($etablissement))
              ->endkey(array($etablissement, array()))->getView('vrac', 'all')->rows as $c) {
       $contrats[] = parent::retrieveDocumentById($c->key[2], $hydrate);
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
      if ($etablissement != $vrac->vendeur_identifiant)
       throw new sfException('le vendeur ne correpond pas à l\'établissement initial');
      if (!preg_match("|^$hash|", $vrac->produit))
       throw new sfException('Le hash du produit ne correpond pas au hash initial ('.$vrac->produit.'<->'.$hash.')');
      return $vrac;
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
