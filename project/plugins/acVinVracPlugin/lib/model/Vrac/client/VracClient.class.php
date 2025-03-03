<?php

class VracClient extends acCouchdbClient {

	/*
	 * @todo Rendre generique les constantes de transaction
	 */
    const TYPE_TRANSACTION_RAISINS = 'raisin';
    const TYPE_TRANSACTION_MOUTS = 'mout';
    const TYPE_TRANSACTION_VIN_VRAC = 'vrac';
    const TYPE_TRANSACTION_VIN_BOUTEILLE = 'vin_bouteille';


    const STATUS_CONTRAT_SOLDE = 'SOLDE';
    const STATUS_CONTRAT_ANNULE = 'ANNULE';
    const STATUS_CONTRAT_NONSOLDE = 'NONSOLDE';
    const STATUS_CONTRAT_ATTENTE_VALIDATION = 'ATTENTE_VALIDATION';
    const STATUS_CONTRAT_ATTENTE_ANNULATION= 'ATTENTE_ANNULATION';

    const FIRST_SV12_CAMPAGNE = "2021-2022";

    public static $types_transaction = array('' => '', VracClient::TYPE_TRANSACTION_RAISINS => 'Raisins',
        VracClient::TYPE_TRANSACTION_MOUTS => 'Moûts',
        VracClient::TYPE_TRANSACTION_VIN_VRAC => 'Vin en vrac',
        VracClient::TYPE_TRANSACTION_VIN_BOUTEILLE => 'Vin conditionné');

    protected $_acteurs = array(self::VRAC_TYPE_VENDEUR, self::VRAC_TYPE_ACHETEUR, self::VRAC_TYPE_COURTIER);
    
    protected $_status_contrat = array(self::STATUS_CONTRAT_SOLDE, 
                                                   self::STATUS_CONTRAT_ANNULE,
                                                   self::STATUS_CONTRAT_NONSOLDE,
                                                   self::STATUS_CONTRAT_ATTENTE_VALIDATION,
                                                   self::STATUS_CONTRAT_ATTENTE_ANNULATION);
    
    protected $_status_contrat_credentials = array(self::STATUS_CONTRAT_SOLDE => array(self::STATUS_CONTRAT_NONSOLDE), 
                                                   self::STATUS_CONTRAT_ANNULE => array(),
                                                   self::STATUS_CONTRAT_NONSOLDE => array(self::STATUS_CONTRAT_SOLDE, self::STATUS_CONTRAT_ANNULE),
                                                   self::STATUS_CONTRAT_ATTENTE_VALIDATION => array(self::STATUS_CONTRAT_NONSOLDE, self::STATUS_CONTRAT_ANNULE),
                                                   self::STATUS_CONTRAT_ATTENTE_ANNULATION => array(self::STATUS_CONTRAT_NONSOLDE));


    const STATUS_VIN_RETIRE = 'retire';
    const STATUS_VIN_LIVRE = 'livre';
    const TRANSACTION_DEFAUT = 'vrac';
    const PRIX_DEFAUT = 'definitif';
    const LABEL_DEFAUT = 'conv';
    const ECHEANCIER_PAIEMENT = 'echeancier_paiement';
    const VRAC_TYPE_VENDEUR = 'vendeur';
    const VRAC_TYPE_ACHETEUR = 'acheteur';
    const VRAC_TYPE_COURTIER = 'mandataire';

    const VERSEMENT_FA_NOUVEAU = 'NC';
    const VERSEMENT_FA_MODIFICATION = 'MC';
    const VERSEMENT_FA_ANNULATION = 'SC';
    const VERSEMENT_FA_TRANSMIS = 'TRANSMIS';

    protected $_statuts_vin = array(VracClient::STATUS_VIN_RETIRE => 'Retiré',
                     VracClient::STATUS_VIN_LIVRE => 'Livré');


    /**
     *
     * @return DRMClient
     */
    public static function getInstance()
    {
      return acCouchdbManager::getClient("Vrac");
    }

    public function getId($numeroContrat, $version = null)
    {
      return 'VRAC-'.$numeroContrat.$this->buildIdVersion($version);
    }
    
    public function buildId($numeroContrat, $version = null) {

        return $this->getId($numeroContrat, $version);
    }
    
	public function buildIdVersion($version) {
        if ($version) {
            return sprintf('-%s', $version);
        }

        return '';
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

    public function getNextNoContratApplication($numeroPluriannuel) {
        $items = $this->startkey('VRAC-'.$numeroPluriannuel.'-A00')->endkey('VRAC-'.$numeroPluriannuel.'-A99')->execute(acCouchdbClient::HYDRATE_JSON);
        return sprintf('%s-A%02d', $numeroPluriannuel, count($items)+1);
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
    
    public function findByNumContrat($num_contrat, $version = null) 
    {
      return $this->find($this->getId($num_contrat, $version));
    }
    
    public function retrieveFromEtablissementsAndHash($etablissement, $hash, $mustActive = true, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $contrats = array();
        $hash = preg_replace('|(couleurs/[^/]*/).*|', '\1', $hash);
        $vracs = VracAllView::getInstance()->findByEtablissement($etablissement);
        foreach ($vracs->rows as $c) {
            if (strpos('/'.$c->key[VracAllView::VRAC_VIEW_PRODUIT], $hash) === false) {
                continue;
            }
            if ($mustActive && $c->key[VracAllView::VRAC_VIEW_STATUT] == self::STATUS_CONTRAT_NONSOLDE) {
               $contrats[] = parent::retrieveDocumentById($c->key[VracAllView::VRAC_VIEW_ID]);
           }
            
      }
      return $contrats;
    }
    
	public function retrieveSoldeFromEtablissementsAndHash($etablissement, $hash, $mustActive = true, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $contrats = array();
        $hash = preg_replace('|(couleurs/[^/]*/).*|', '\1', $hash);
        $vracs = VracAllView::getInstance()->findByEtablissement($etablissement)->rows;
        $vracs = array_merge($vracs, VracAllView::getInstance()->findSoldeByEtablissement($etablissement)->rows);
        foreach ($vracs as $c) {
            if (strpos('/'.$c->key[VracAllView::VRAC_VIEW_PRODUIT], $hash) === false) {
                continue;
            }
            if ($mustActive && $c->key[VracAllView::VRAC_VIEW_STATUT] == self::STATUS_CONTRAT_SOLDE) {
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
       $vrac->vendeur_identifiant = $etablissement;
       $vrac->numero_contrat = $id;
       $vrac->produit = $hash;
      }
      if ($etablissement != $vrac->vendeur_identifiant)
       throw new sfException('le vendeur ne correpond pas à l\'établissement initial');
      if (!preg_match("|^$hash|", $vrac->produit))
       throw new sfException('Le hash du produit ne correpond pas au hash initial ('.$vrac->produit.'<->'.$hash.')');
      return $vrac;
    }

    public function getStatusContrat($withZero = false) {
        if ($withZero) {
            $statuts = $this->_status_contrat;
            $statuts[] = 0;
            return $statuts;
        }
    	return $this->_status_contrat;
    }

    public function getStatusContratCredentials() {
    	return $this->_status_contrat_credentials;
    }
    /*
     * Methodes de l'ancien plugin
     */

    public function retrieveById($id, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return parent::retrieveDocumentById('VRAC-'.$id, $hydrate);
    }

    public function getActeurs() {
		return $this->_acteurs;
    }

    public function getStatutsVin() {
		return $this->_statuts_vin;
    }
    
	public function findAll() 
    {
        return $this->getView('vrac', 'all');
    }
    
    public static function matchStatut($statut)
    {
      	if (preg_match('/non/i', $statut)) {
        	return self::STATUS_CONTRAT_NONSOLDE;
      	}
      	if (preg_match('/solde/i', $statut)) {
        	return self::STATUS_CONTRAT_SOLDE;
      	}
      	if (preg_match('/annule/i', $statut)) {
        	return self::STATUS_CONTRAT_ANNULE;
      	}
      	if (preg_match('/attente/i', $statut)) {
        	return self::STATUS_CONTRAT_ATTENTE_VALIDATION;
      	}
		return $statut;
    }
    
	public function findMasterByVisa($visa, $version = null) {
        $contrat = $this->findByNumContrat($visa, $version);
        return $contrat;
    }

    public function buildVersion($rectificative, $modificative) {

        return Vrac::buildVersion($rectificative, $modificative);
    }

    public function getRectificative($version) {

        return Vrac::buildRectificative($version);
    }

    public function getModificative($version) {

        return Vrac::buildModificative($version);
    }



    public static function sortVracId($a, $b)
    {
    	$ma1 = substr($a->value[VracHistoryView::VRAC_VIEW_NUM], 0, 11);
    	$mb1 = substr($b->value[VracHistoryView::VRAC_VIEW_NUM], 0, 11);
    	$versionA = $a->value[VracHistoryView::VRAC_VERSION];
    	$versionB = $b->value[VracHistoryView::VRAC_VERSION];
        $ma = substr($versionA, 1);
        $mb = substr($versionB, 1);

    	if ((!$versionA && !$versionB) || $ma1 != $mb1) {
    		if ($ma1 > $mb1) {
                return -1;
            }
            if ($ma1 < $mb1) {
            	return 1;
            }
            return 0;
    	}

    	if (!$versionA) {
    		return 1;
    	}

    	if (!$versionB) {
    		return -1;
    	}

    	return ($ma < $mb)? 1 : -1;

    }

    public function retrieveAllVracs($interpro, $startDate) {
        return VracDateView::getInstance()->findByInterproAndDate($interpro, $startDate);
    }

    public function listCampagneByEtablissementId($identifiant) {
        return ConfigurationClient::getInstance()->getCampagneVinicole()->fillCampagnesList(self::FIRST_SV12_CAMPAGNE);
    }

    // Compatibilite module giilda SV12
    public function retrieveBySoussigneAndType($soussigneId, $campagne, $type, $limit = null) {
        $obj = new stdClass();
        $obj->rows = array();
        return $obj;
    }

    public static function getTypes() {
        return self::$types_transaction;
    }

    public function retrieveByCVIAndMillesime($cvi, $millesime, $hash = null, $mustActive = true, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $contrats = array();
        $vracs=array();

        $etablissements = EtablissementIdentifiantView::getInstance()->findByIdentifiant($cvi);

        foreach($etablissements->rows as $e){
            $id = str_replace('ETABLISSEMENT-', '', $e->id);
            $vracs = array_merge($vracs,VracHistoryView::getInstance()->findByEtablissement($id));

        }

        foreach ($vracs as $c) {
            if ($mustActive && $c->value[VracHistoryView::VRAC_VIEW_STATUT] != self::STATUS_CONTRAT_NONSOLDE) {
                continue;
            }
            if($hash && strpos($c->value[VracHistoryView::VRAC_VIEW_PRODUIT_ID], $hash) === false) {
                continue;
            }
            if($c->value[VracHistoryView::VRAC_VIEW_MILLESIME] != $millesime){
                continue;
            }
            $contrats[] = $c;
        }
        return $contrats;
    }


 }