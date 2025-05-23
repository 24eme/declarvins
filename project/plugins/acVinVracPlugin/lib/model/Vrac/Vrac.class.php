<?php
class Vrac extends BaseVrac implements InterfaceVersionDocument
{
	const MODE_DE_SAISIE_PAPIER = 'PAPIER';
    const MODE_DE_SAISIE_DTI = 'DTI';
    const MODE_DE_SAISIE_EDI = 'EDI';

    protected static $_mode_de_saisie_libelles = array (
    									self::MODE_DE_SAISIE_PAPIER => 'par l\'interprofession (papier)',
    									self::MODE_DE_SAISIE_DTI => 'via Declarvins (DTI)',
    									self::MODE_DE_SAISIE_EDI => 'via votre logiciel (EDI)');
    protected $version_document = null;
    protected $suivante = null;

	public function __construct() {
        parent::__construct();
        $this->version_document = new VersionDocument($this);
    }

    public function constructId()
    {
        $this->set('_id', $this->makeId());
    }

    public function makeId()
    {
    	$id = 'VRAC-'.$this->numero_contrat;
    	if ($this->version) {
    		$id .= '-'.$this->version;
    	}
    	return $id;
    }

		public function hasAcompteInfo() {
			return ($this->isConditionneIr() && $this->type_transaction == 'raisin')? true : false;
		}

    public function initClauses() {
        $this->remove('clauses');
        $this->remove('clauses_complementaires');
        $this->add('clauses');
        $this->add('clauses_complementaires');
        $configuration = $this->getVracConfiguration();
        if (!$configuration) return;
        $clausesMask = $configuration->getClausesMask($this->getClausesMaskConf());
        $this->clauses = $configuration->get('clauses');
        foreach($clausesMask as $mask) {
            if ($this->clauses->exist($mask)) {
                $this->clauses->remove($mask);
            }
        }
        $cc = array();
        foreach ($configuration->get('clauses_complementaires') as $k => $v) {
            $cc[$k] = $k;
        }
        $this->clauses_complementaires = implode(',', $cc);
    }

    public function getClausesMaskConf() {
        $conf = '';
        $conf .= ($this->contrat_pluriannuel)? '1' : '0';
        $conf .= ($this->type_transaction == 'vrac')? '1' : '0';
        $conf .= ($this->reference_contrat_pluriannuel)? '1' : '0';
        return $conf;
    }

    public function getVracConfiguration() {
        $interpro = $this->getProduitInterpro();
        if (!$interpro) {
            $interpro = ($this->interpro)? InterproClient::getInstance()->find($this->interpro) : null;
        }
        if (!$interpro) {
            return;
        }
        if (!ConfigurationClient::getCurrent()->vrac->interpro->exist($interpro->_id)) {
            return;
        }
        return ConfigurationClient::getCurrent()->vrac->interpro->get($interpro->_id);
    }

    public function getLibellesMentions()
    {
        $mentions = $this->getMentions();
        $result = array();
        foreach ($mentions as $mention) {
            if ($mention == 'autre') {
                $mention = ($this->mentions_libelle_autre)? $this->mentions_libelle_autre : 'Autre';
            }
            if ($mention == 'chdo') {
                $mention = ($this->mentions_libelle_chdo)? $this->mentions_libelle_chdo : 'Domaine / autre terme reglementé';
            }
            if ($mention == 'marque') {
                $mention = ($this->mentions_libelle_marque)? $this->mentions_libelle_marque : 'Marque';
            }
            $result[] = $mention;
        }
        return $result;
    }

    public function getLibellesLabels()
    {
        $labels = $this->labels_arr;
        $result = array();
        foreach ($labels as $label) {
            if ($label == 'autre') {
                $label = ($this->labels_libelle_autre)? $this->labels_libelle_autre : 'Autre';
            }
            $result[] = $label;
        }
        return $result;
    }

    public function getProduitObject()
    {
        $configuration = ConfigurationClient::getCurrent();
        return $configuration->getConfigurationProduit($this->produit);
    }

    public function getCepagesProduit($withDefault = false)
    {
    	$configuration = ConfigurationClient::getCurrent();
    	$cepages = array();
    	if ($produit = $this->getProduitObject()) {
    		if (!$withDefault) {
    			$produits = array();
    			$p = $produit->getCouleur()->getProduits();
    			foreach ($p as $k => $v) {
    				if ($v->getKey() != ConfigurationProduit::DEFAULT_KEY) {
    					$produits[$k] = $v;
    				}
    			}
    		} else {
    			$produits = $produit->getCouleur()->getProduits();
    		}
    		$cepages = $configuration->formatWithCode($produits, "%ce%");
    	}
    	return $cepages;
    }

    public function getLibelleProduit($format = "%g% %a% %l% %co% %ce%", $force = false)
    {
    	if ($this->produit_libelle && !$force) {
    		return $this->produit_libelle;
    	}
    	$produit = $this->getProduitObject();
    	return ConfigurationProduitClient::getInstance()->format($produit->getLibelles(), array(), $format);
    }

    public function getVendeurObject()
    {
        return EtablissementClient::getInstance()->find($this->vendeur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }

    public function getAcheteurObject()
    {
        return EtablissementClient::getInstance()->find($this->acheteur_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }

    public function getMandataireObject()
    {
        return EtablissementClient::getInstance()->find($this->mandataire_identifiant,acCouchdbClient::HYDRATE_DOCUMENT);
    }

    public function vendeurHasCompteActif()
    {
        $etablissement = $this->getVendeurObject();
        if ($etablissement && $compte = $etablissement->getCompteObject()) {
            return ($compte->statut == _Compte::STATUT_INSCRIT);
        }
        return false;
    }

    public function acheteurHasCompteActif()
    {
        $etablissement = $this->getAcheteurObject();
        if ($etablissement && $compte = $etablissement->getCompteObject()) {
            return ($compte->statut == _Compte::STATUT_INSCRIT);
        }
        return false;
    }

    public function mandataireHasCompteActif()
    {
    	$etablissement = $this->getMandataireObject();
    	if ($compte = $etablissement->getCompteObject()) {
    		return ($compte->statut == _Compte::STATUT_INSCRIT);
    	}
    	return false;
    }

    public function getSoussigneObjectById($soussigneId)
    {
        return EtablissementClient::getInstance()->find($soussigneId,acCouchdbClient::HYDRATE_DOCUMENT);
    }
    public function getVendeurDepartement()
    {
    	if($this->vendeur->code_postal) {
          return substr($this->vendeur->code_postal, 0, 2);
        }

        return null;
    }

    public function getCreateur($object = false)
    {
    	if ($this->vous_etes) {
    		if ($id = $this->get($this->vous_etes.'_identifiant')) {
    			return ($object)? $this->getSoussigneObjectById($id) : $id;
    		}
    	}
    	return null;
    }

	public function isCreateur($etablissement = null) {
		return ($etablissement && $etablissement->identifiant ==  $this->get($this->vous_etes.'_identifiant'))? true : false;
	}

    public function getVendeurInterpro()
    {
        return $this->getVendeurObject()->interpro;
    }

    public function getProduitInterpro()
    {
    	if ($this->produit) {
    		$produit = $this->getProduitObject();
			if(!$produit) {
                throw new Exception("Produit non trouvé : ".$this->produit." (".$this->_id.")");
            }

      		return $produit->getDocument()->getInterproObject();
    	}
    	return null;
    }

    public function isConditionneIvse()
    {
    	if ($interpro = $this->getProduitInterpro()) {
    		if ($interpro->identifiant == 'IVSE') {
    			return true;
    		}
    	}
    	return false;
    }

    public function isConditionneIr()
    {
    	if ($interpro = $this->getProduitInterpro()) {
    		if ($interpro->identifiant == 'IR') {
    			return true;
    		}
    	}
    	return false;

    }

    public function isConditionneCivp()
    {
    	if ($interpro = $this->getProduitInterpro()) {
    		if ($interpro->identifiant == 'CIVP') {
    			return true;
    		}
    	}
    	return false;

    }

    public function storeSoussignesInformations() {
    	$acheteur = $this->getAcheteurObject();
    	$vendeur = $this->getVendeurObject();
    	$mandataire = $this->getMandataireObject();
      	$this->storeSoussigneInformations('acheteur', $acheteur);
      	$this->storeSoussigneInformations('vendeur', $vendeur);
     	$this->storeSoussigneInformations('mandataire', $mandataire);
     	if ($acheteur->compte == $vendeur->compte && !$this->hasVersion()) {
     		$this->cas_particulier = 'interne';
     	}
    }

    public function storeSoussigneInformations($type, $etablissement)
    {

    	   if (!$this->mandataire_exist && !$this->mandataire_identifiant) {
    	   	$this->remove('mandataire');
    	   	$this->add('mandataire');
    	   }
    	   $informations = $this->get($type);

         if(!$etablissement) {

          return null;
         }
         if ($this->exist($type.'_type')) {
         	$this->{$type.'_type'} = $etablissement->famille;
         }

        if ($informations->exist('nom')) $informations->nom = $etablissement->nom;
      	if ($informations->exist('raison_sociale')) $informations->raison_sociale = $etablissement->raison_sociale;
      	if ($informations->exist('siret')) $informations->siret = $etablissement->siret;
      	if ($informations->exist('cvi')) $informations->cvi = $etablissement->cvi;
      	if ($informations->exist('num_accise')) $informations->num_accise = $etablissement->no_accises;
      	if ($informations->exist('num_tva_intracomm')) $informations->num_tva_intracomm = $etablissement->no_tva_intracommunautaire;
      	if ($informations->exist('no_carte_professionnelle')) $informations->no_carte_professionnelle = $etablissement->no_carte_professionnelle;
      	if ($informations->exist('adresse')) $informations->adresse = $etablissement->siege->adresse;
      	if ($informations->exist('code_postal')) $informations->code_postal = $etablissement->siege->code_postal;
      	if ($informations->exist('commune')) $informations->commune = $etablissement->siege->commune;
      	if ($informations->exist('pays')) $informations->pays = $etablissement->siege->pays;
      	if ($informations->exist('telephone')) $informations->telephone = $etablissement->telephone;
      	if ($informations->exist('fax')) $informations->fax = $etablissement->fax;
      	if ($informations->exist('email')) $informations->email = $etablissement->email;
      	if ($informations->exist('famille')) $informations->famille = $etablissement->famille;
      	if ($informations->exist('sous_famille')) $informations->sous_famille = $etablissement->sous_famille;
      	if ($informations->exist('zones') && $etablissement->exist('zones')) {
      		foreach ($etablissement->zones as $zoneId => $zone) {
      			$informations->zones->add($zoneId, $zone->libelle);
      		}
      	}
    }

    public function getCvoUnitaire() {
        return round($this->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 4);
    }

    public function getTotalUnitaire() {
    	return ($this->type_transaction == 'vrac' && $this->premiere_mise_en_marche && $this->isConditionneIr())? round($this->prix_unitaire + $this->getCvoUnitaire(), 2) : round($this->prix_unitaire, 2);
    }

    public function setDetailProduit($produit)
    {
    	$this->produit_detail->appellation->code = $produit->getAppellation()->code;
    	$this->produit_detail->appellation->libelle = $produit->getAppellation()->libelle;
    	$this->produit_detail->genre->code = $produit->getGenre()->code;
    	$this->produit_detail->genre->libelle = $produit->getGenre()->libelle;
    	$this->produit_detail->certification->code = $produit->getCertification()->code;
    	$this->produit_detail->certification->libelle = $produit->getCertification()->libelle;
    	$this->produit_detail->lieu->code = $produit->getLieu()->code;
    	$this->produit_detail->lieu->libelle = $produit->getLieu()->libelle;
    	$this->produit_detail->couleur->code = $produit->getCouleur()->code;
    	$this->produit_detail->couleur->libelle = $produit->getCouleur()->libelle;
    	$this->produit_detail->cepage->code = $produit->code;
    	$this->produit_detail->cepage->libelle = $produit->libelle;
    	if ($inao = $produit->getInao()) {
    		if (strlen($inao) == 5) {
    			$inao = $inao.' ';
    		}
    		$this->produit_detail->codes->inao = $inao;
    	}
    	if ($lf = $produit->getLibelleFiscal()) {
    		$this->produit_detail->codes->libelle_fiscal = $lf;
    	}
    }

    public function update($params = array()) {
      parent::update($params);
      $vol = ($this->poids)? $this->poids : $this->volume_propose;
      $this->prix_total_net = round($this->prix_unitaire * $vol, 2);
      if ($this->type_transaction != 'vrac'||!$this->premiere_mise_en_marche) {
          $this->has_cotisation_cvo = 0;
      }
	  if ($this->has_cotisation_cvo && $this->part_cvo > 0) {
	  	$this->prix_total = round($vol * $this->getTotalUnitaire(), 2);
	  } else {
      	$this->prix_total = round($this->prix_unitaire * $vol, 2);
	  }
    }

    public function getPrixTotalCalc() {
        return round($this->getTotalUnitaire() * $this->volume_propose, 2);
    }

    public function normalizeNumeric()
    {
    	$this->prix_unitaire = ($this->prix_unitaire)? $this->prix_unitaire * 1 : 0;
    	$this->prix_total = ($this->prix_total)? $this->prix_total * 1 : 0;
    	$this->prix_total_net = ($this->prix_total_net)? $this->prix_total_net * 1 : 0;
    	$this->volume_propose = ($this->volume_propose)? $this->volume_propose * 1 : 0;
    	$this->volume_enleve = ($this->volume_enleve)? $this->volume_enleve * 1 : 0;
    }

    public function annuler($user, $etablissement = null, $force = false) {
    	$this->getOrAdd('annulation');
    	$this->valide->statut = VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION;
    	if (!$this->annulation->identifiant) {
    		$this->annulation->identifiant = $user->getCompte()->_id;
    	}
    	if (!$this->annulation->etablissement && $etablissement) {
    		$this->annulation->etablissement = $etablissement->_id;
    	}
    	$acteurs = VracClient::getInstance()->getActeurs();
      	if (!$this->mandataire_exist) {
      		unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
      	}
    	if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR) || $force) {
    		$this->annulation->date_annulation = date('c');
    		//$this->date_stats = $this->annulation->date_annulation;
    		foreach ($acteurs as $acteur) {
    			$validateur = 'date_annulation_'.$acteur;
    			if (!$this->annulation->get($validateur)) {
    				$this->annulation->{$validateur} = $this->annulation->date_annulation;
    			}
    		}
    		$this->valide->statut = VracClient::STATUS_CONTRAT_ANNULE;
            $this->add('versement_fa', VracClient::VERSEMENT_FA_ANNULATION);
    	} else {
    		if ($etablissement) {
    			$type = $this->getTypeByEtablissement($etablissement->identifiant);
    			if ($type && in_array($type, $acteurs)) {
	    			$validateur = 'date_annulation_'.$type;
	    			$this->annulation->{$validateur} = date('c');
	    		}
    		} else {
	    		if ($this->vous_etes && in_array($this->vous_etes, $acteurs)) {
	    			$validateur = 'date_annulation_'.$this->vous_etes;
	    			$this->annulation->{$validateur} = date('c');
	    		}
    		}
    		$statut_annule = true;
	      	foreach ($acteurs as $acteur) {
	      		$validateur = 'date_annulation_'.$acteur;
	      		if (!$this->annulation->get($validateur)) {
	      			$statut_annule = false;
	      			break;
	      		}
	      	}
	      	if ($statut_annule) {
	      		$this->valide->statut = VracClient::STATUS_CONTRAT_ANNULE;
	    		$this->annulation->date_annulation = date('c');
	            $this->add('versement_fa', VracClient::VERSEMENT_FA_ANNULATION);
    			//$this->date_stats = $this->annulation->date_annulation;
    			//$this->valide->date_validation = $this->annulation->date_annulation;
	      	}
    	}
    }

    public function validate($user, $etablissement = null) {
    	$this->valide->statut = VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION;
    	if (!$this->valide->date_saisie) {
    		$this->valide->date_saisie = date('c');
    	}
    	$this->valide->identifiant = $user->getCompte()->_id;
    	$acteurs = VracClient::getInstance()->getActeurs();
      	if (!$this->mandataire_exist) {
      		unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
      	}
        $this->updateVersementFa();
    	if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$this->isRectificative()) {
    		$this->mode_de_saisie = self::MODE_DE_SAISIE_PAPIER;
    		if (!$this->date_signature) {
    			$this->date_signature = date('c');
    		}
    		if (!$this->date_stats) {
    			$this->date_stats = $this->date_signature;
    		}
    		foreach ($acteurs as $acteur) {
    			$validateur = 'date_validation_'.$acteur;
    			if (!$this->valide->get($validateur)) {
    				$this->valide->{$validateur} = $this->date_signature;
    			}
    		}
    		$this->valide->statut = ($this->isPluriannuel())? VracClient::STATUS_CONTRAT_SOLDE : VracClient::STATUS_CONTRAT_NONSOLDE;
    		$this->valide->date_validation = ($this->valide->date_saisie)? $this->valide->date_saisie : $this->date_signature;
    		if (!$this->mandataire_exist) {
    			$this->remove('mandataire');
    			$this->add('mandataire');
    			$this->mandataire_identifiant = null;
    		}
    		$this->updateReferente();
    		$this->updateEnlevements();
    	} else {
    		if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    			$this->mode_de_saisie = self::MODE_DE_SAISIE_PAPIER;
    		} else {
    			$this->mode_de_saisie = self::MODE_DE_SAISIE_DTI;
    		}
    		if ($etablissement) {
    			$type = $this->getTypeByEtablissement($etablissement->identifiant);
    			if ($type && in_array($type, $acteurs)) {
	    			$validateur = 'date_validation_'.$type;
	    			$this->valide->{$validateur} = date('c');
	    		}
    		} else {
	    		if ($this->vous_etes && in_array($this->vous_etes, $acteurs)) {
	    			$validateur = 'date_validation_'.$this->vous_etes;
	    			$this->valide->{$validateur} = date('c');
	    		}
    		}
    	}
    }

    public function validateEdi()
    {
    	$this->vous_etes = VracClient::VRAC_TYPE_VENDEUR;
    	$this->date_signature = date('c');
    	$this->valide->statut = ($this->isPluriannuel())? VracClient::STATUS_CONTRAT_SOLDE : VracClient::STATUS_CONTRAT_NONSOLDE;
    	$this->valide->date_saisie = $this->date_signature;
    	$this->valide->date_validation = $this->date_signature;
    	$this->valide->date_validation_vendeur = $this->date_signature;
    	$this->valide->date_validation_acheteur = $this->date_signature;
    	$this->mode_de_saisie = self::MODE_DE_SAISIE_EDI;
    	if ($interpro = $this->getProduitInterpro()) {
    		$this->interpro = $interpro->_id;
    	}
    }

    public function getHasCotisationCvo() {
        $interpro = $this->interpro;
        if ($i = $this->getProduitInterpro()) {
            $interpro = $i->_id;
        }
        if ($interpro == 'INTERPRO-IR' || $interpro == 'INTERPRO-CIVP') {
            return 1;
        }
        return $this->_get('has_cotisation_cvo');
    }

	public function getTypeByEtablissement($identifiant)
	{
		$type = null;
		if ($this->acheteur_identifiant == $identifiant) {
			$type = 'acheteur';
		}
		if ($this->vendeur_identifiant == $identifiant) {
			$type = 'vendeur';
		}
		if ($this->mandataire_identifiant == $identifiant) {
			$type = 'mandataire';
		}
		return $type;
	}

    public function devalide() {
        $this->valide->statut = null;
        $this->valide->identifiant = null;
        $this->valide->date_validation = null;
    	$acteurs = VracClient::getInstance()->getActeurs();
    	foreach ($acteurs as $acteur) {
    		$validateur = 'date_validation_'.$acteur;
    		$this->valide->{$validateur} = null;
    	}
    	if ($this->exist('oioc')) {
	    	$this->remove('oioc');
	    	$this->add('oioc');
    	}
    }

    public function updateStatut() {
      $acteurs = VracClient::getInstance()->getActeurs();
      if (!$this->mandataire_exist) {
      	unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
      }
      $statut_valide = true;
      foreach ($acteurs as $acteur) {
      	$validateur = 'date_validation_'.$acteur;
      	if (!$this->valide->get($validateur)) {
      		$statut_valide = false;
      		break;
      	}
      }
      if ($statut_valide) {
        $this->valide->statut = ($this->isPluriannuel())? VracClient::STATUS_CONTRAT_SOLDE : VracClient::STATUS_CONTRAT_NONSOLDE;
    	$this->valide->date_validation = date('c');
    	$this->date_signature = $this->valide->date_validation;
    	if (!$this->hasVersion()) {
    	$this->date_stats = $this->valide->date_validation;
    	}
    	if (!$this->mandataire_exist) {
    		$this->remove('mandataire');
    		$this->add('mandataire');
    		$this->mandataire_identifiant = null;
    	}
    	$this->updateReferente();
    	$this->updateEnlevements();
    	$this->setOioc();
      }
    }

	public function getCampagne() {
		$dateForCampagne = $this->date_stats;
		if (!$dateForCampagne) {
			$dateForCampagne = $this->valide->date_validation;
		}
		if (!$dateForCampagne) {
			$dateForCampagne = $this->date_signature;
		}
		if (!$dateForCampagne) {
			$dateForCampagne = $this->valide->date_saisie;
		}
		if (!$dateForCampagne) {
			throw new sfException('Impossible de determiner la campagne pour le contrat '.$this->_id);
		}
		$cc = new CampagneManager('08-01');
        return $cc->getCampagneByDate($dateForCampagne);
	}

    public function updateReferente()
    {
    	$this->referente = 1;
    	if ($mother = $this->getMother()) {
    		$mother->referente = 0;
    		$mother->valide->statut = VracClient::STATUS_CONTRAT_ANNULE;
    		//$mother->date_stats = date('c');
    		//$mother->valide->date_validation = $mother->date_stats;
    		$mother->save(false);
    	}
    }

    public function hasOioc()
    {
    	$produit = $this->getProduitObject();
    	if ($organisme = $produit->getCurrentOrganisme($this->valide->date_saisie, true)) {
	    	return ($this->type_transaction == 'vrac' && ($this->type_retiraison == 'vrac' || !$this->type_retiraison));
    	}
    	return false;

    }

    public function setOioc()
    {
    	$produit = $this->getProduitObject();
    	if ($organisme = $produit->getCurrentOrganisme($this->valide->date_saisie, true)) {
				if ($this->type_transaction == 'vrac' && ($this->type_retiraison == 'vrac' || !$this->type_retiraison)) {
	    		$oioc = $this->getOrAdd('oioc');
	    		$oioc->identifiant = str_replace(OIOC::OIOC_KEY, '', $organisme->oioc);
	    		$oioc->statut = OIOC::STATUT_EDI;
	    		$oioc->date_traitement = date('c');
    		}
    	}
    }

    public function hasEnlevements()
    {
    	if ($this->exist('enlevements')) {
    		return (count($this->enlevements) > 0)? true : false;
    	}
    	return false;
    }


    protected function updateStatutSolde() {
        if ($this->volume_propose > 0 && round($this->volume_enleve, 2) >= round($this->volume_propose, 2) && $this->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
        	$this->valide->statut = VracClient::STATUS_CONTRAT_SOLDE;
        } elseif (!$this->isPluriannuel() && round($this->volume_enleve, 2) < round($this->volume_propose, 2) && $this->valide->statut == VracClient::STATUS_CONTRAT_SOLDE) {
        	$this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
        }
    }

    public function save($updateStatutSolde = true) {
    	$this->updateVolumeEnleve();
    	if ($updateStatutSolde) {
    		$this->updateStatutSolde();
    	}
    	$this->part_cvo = floatval($this->part_cvo);
    	$this->has_cotisation_cvo = $this->getHasCotisationCvo();
    	if (!$this->valide->date_saisie) {
    		$this->valide->date_saisie = date('c');
    	}
    	$this->normalizeNumeric();
    	$this->has_transaction = 0;
    	if ($this->produit && $this->interpro && $this->type_transaction && $this->interpro == 'INTERPRO-CIVP' && $this->type_transaction == 'vrac') {
    	    $this->has_transaction = 1;
    	}
			$manageAttachments = (($this->isNew() && $this->version)||($this->isNew() && $this->isAdossePluriannuel()));
    	parent::save();
			if ($manageAttachments) {
				if ($previous = $this->findDocumentByVersion($this->getPreviousVersion())) {
					if ($previous->exist('_attachments')) {
						$files = [];
						foreach ($previous->_attachments as $filename => $fileinfos) {
								$path = "/tmp/$filename";
								file_put_contents($path, file_get_contents($previous->getAttachmentUri($filename)));
								$files[$filename] = $path;
						}
						$this->add('_attachments');
						foreach ($files as $filename => $file) {
							$mime = mime_content_type($file);
							$this->storeAttachment($file, $mime, $filename);
							unlink($file);
						}
						parent::save();
					}
				}
			}
    }

    protected function updateVolumeEnleve() {
    	if (!$this->exist('enlevements')) {
    		return;
    	}
    	$vol = 0;
    	foreach ($this->enlevements as $enlevement) {
    		$vol += $enlevement->volume;
    	}
    	$this->volume_enleve = $vol;
    }

    public function updateEnlevements()
    {
    	$identifiant = $this->numero_contrat;
	    if ($this->exist('version') && $this->version) {
	    	$identifiant .= '-'.$this->version;
	    }
	    $delete = array();
		if ($this->hasVersion()) {
			if ($previous = $this->getMother()) {
				$enlevements = $this->getOrAdd('enlevements');
				$id = $previous->numero_contrat;
	            if ($previous->exist('version') && $previous->version) {
	            	$id .= '-'.$previous->version;
	            }
				foreach ($enlevements as $idDrm => $infos) {
					if ($drm = DRMClient::getInstance()->find($idDrm)) {
						foreach ($drm->getDetails() as $detail) {
	            			foreach ($detail->vrac as $numero => $vrac) {
	            				if (trim($numero) == trim($id)) {
	            					$detailVrac = $detail->vrac->get($numero);
	            					$detail->vrac->add(trim($identifiant), $vrac);
	            					$delete[$detailVrac->gethash()] = $drm;
	            				}
	            			}
						}
					}
				}
			}
		}
		foreach ($delete as $hash => $drm) {
			$drm->remove($hash);
			$drm->save();
		}
    }

    public function isSolde() {
    	return ($this->valide->statut == VracClient::STATUS_CONTRAT_SOLDE);
    }

    public function desolder() {
    	$this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
    }

    public function isEnCoursSaisie() {

      return $this->valide->statut == null;
    }

    public function isValide() {
    	return ($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION && $this->valide->statut != VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION)? true : false;
    }

    public function isEditable() {
      if($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {
        return false;
      }

    	return !($this->volume_enleve > 0);
    }

    public function isModifiableVolume() {
        if($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {

            return false;
        }

        if($this->mode_de_saisie != self::MODE_DE_SAISIE_PAPIER) {

            return false;
        }

        return true;
    }

    public function isVisualisable() {
      return ($this->valide->statut)? true : false;
    }

    public function getEuSaisieDate() {
		return strftime('%d/%m/%Y', strtotime($this->valide->date_saisie));
    }
    public function getModeDeSaisieLibelle() {
    	$libelles = self::getModeDeSaisieLibelles();
    	return (isset($libelles[$this->mode_de_saisie]))? $libelles[$this->mode_de_saisie] : null;
    }

    public function hasAdresseLivraison() {
    	return ($this->adresse_livraison->adresse || $this->adresse_livraison->code_postal || $this->adresse_livraison->commune);
    }

    public function hasAdresseStockage() {
    	return ($this->adresse_stockage->adresse || $this->adresse_stockage->code_postal || $this->adresse_stockage->commune);
    }

    public function integreVolumeEnleve($volume) {
    	$this->volume_enleve = $this->volume_enleve + $volume;
    }

    public function soustraitVolumeEnleve($volume) {
    	$this->volume_enleve = $this->volume_enleve - $volume;
    }


    public static function getModeDeSaisieLibelles() {
		return self::$_mode_de_saisie_libelles;
    }

    public function setImportableVendeur($identifiant = null, $ea = null, $siretCvi = null) {
    	$referent = null;
    	if ($identifiant) {
    		$referent = EtablissementClient::getInstance()->find($identifiant);
    	}
    	if (!$referent) {
    		$referent = ConfigurationClient::getCurrent()->identifyEtablissement($identifiant);
    	}
    	if (!$referent && $ea) {
    		$referent = ConfigurationClient::getCurrent()->identifyEtablissement($ea);
    	}
    	if (!$referent && $siretCvi) {
    		$referent = ConfigurationClient::getCurrent()->identifyEtablissement($siretCvi);
    	}
    	if (!$referent) {
    		return false;
    	}
    	$this->vendeur_identifiant = $referent->identifiant;
    	$this->storeSoussigneInformations('vendeur', $referent);
    	return true;
    }

    /*     * ** VERSION *** */

    public static function buildVersion($rectificative, $modificative) {

        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public static function buildRectificative($version) {

        return VersionDocument::buildRectificative($version);
    }

    public static function buildModificative($version) {

        return VersionDocument::buildModificative($version);
    }

    public function getVersion() {

        return $this->_get('version');
    }

    public function hasVersion() {

        return $this->version_document->hasVersion();
    }

    public function isVersionnable() {
        return true;
    }

    public function getRectificative() {

        return $this->version_document->getRectificative();
    }

    public function isRectificative() {

        return $this->version_document->isRectificative();
    }

    public function isRectifiable() {

        return $this->version_document->isRectifiable();
    }

    public function getModificative() {

        return $this->version_document->getModificative();
    }

    public function isModificative() {

        return $this->version_document->isModificative();
    }

    public function isModifiable() {

        return $this->version_document->isModifiable();
    }

    public function isRectificativeAndModificative() {
        return $this->isModificative() && $this->isRectificative();
    }

    public function getPreviousVersion() {

        return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        throw new sfException('inutile');
    }

    public function needNextVersion() {

        return $this->version_document->needNextVersion();
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return VracClient::getInstance()->findMasterByVisa($this->numero_contrat);
    }

    public function findDocumentByVersion($version) {
        if ($doc = $this->getContratPluriannelReferent()) {
            return $doc;
        }
        return VracClient::getInstance()->find(VracClient::getInstance()->buildId($this->numero_contrat, $version));
    }

    public function getMother() {

        return $this->version_document->getMother();
    }

    public function motherGet($hash) {

        return $this->version_document->motherGet($hash);
    }

    public function motherExist($hash) {

        return $this->version_document->motherExist($hash);
    }

    public function motherHasChanged() {
        return false;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative() {
        $doc = $this->version_document->generateRectificative();
        return $doc;
    }

    public function generateModificative() {
        $doc = $this->version_document->generateModificative();
        return $doc;
    }

    public function generateNextVersion() {
        if (!$this->hasVersion()) {
            $next = $this->version_document->generateModificativeSuivante();
        } else {
            $next = $this->version_document->generateNextVersion();
        }
        return $next;
    }

    public function listenerGenerateVersion($document) {
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        $document->update();
    }

	public function getSuivante() {
        if (is_null($this->suivante)) {
            if (!$this->numero_contrat) {
                return null;
            }
            $this->suivante = VracClient::getInstance()->findMasterByVisa($this->numero_contrat);
        }

        return $this->suivante;
    }

    /*     * ** FIN DE VERSION *** */

    public function storeAnnexe($file, $name) {
        if (!is_file($file)) {
            throw new sfException($file." n'est pas un fichier valide");
        }
        $pathinfos = pathinfo($file);
        $extension = (isset($pathinfos['extension']) && $pathinfos['extension'])? strtolower($pathinfos['extension']): null;
        if ($extension) {
            $name .= ".$extension";
        }
        if ($this->deleteAnnexe($name)) {
            $this->save();
        }
        $this->storeAttachment($file, mime_content_type($file), $name);
    }

    public function deleteAnnexe($annexe) {
        if ($filename = $this->getAnnexeFilename($annexe)) {
            $this->_attachments->remove($filename);
            return true;
        }
        return false;
    }

    public function getAnnexeFilename($annexe) {
        if(!$this->exist('_attachments')) {
            return null;
        }
        foreach ($this->_attachments as $filename => $fileinfos) {
            if (strpos($filename, $annexe) !== false) return $filename;
        }
        return null;
    }

    public function isPrimeur() {
        return in_array('prim', $this->mentions);
    }

    public function isBio() {
        return in_array('biol', $this->labels_arr);
    }

    public function updateVersementFa(){
        if (!$this->exist('versement_fa') || !$this->versement_fa) {
            $this->add('versement_fa', VracClient::VERSEMENT_FA_NOUVEAU);
        }
        if ($this->exist('versement_fa') && $this->versement_fa == VracClient::VERSEMENT_FA_TRANSMIS) {
            $this->versement_fa = VracClient::VERSEMENT_FA_MODIFICATION;
        }
    }

    public function isVolumesAppellationsEnAlerte() {
        $isTypeNonVrac = ($this->type_transaction != 'vrac');
        $isContratInterne = ($this->cas_particulier == 'interne');
        $retiraisonTireBouche = ($this->type_retiraison == 'tire_bouche');
        if ($isTypeNonVrac||$isContratInterne||$retiraisonTireBouche||!$this->produit) {
            return false;
        }
        $appellation = $this->produit_detail->appellation->code;
        $volumesAppellations = VracConfiguration::getInstance()->getPrixAppellations();
        if (isset($volumesAppellations[$appellation])) {
            if (($this->prix_unitaire < $volumesAppellations[$appellation]['min'])||($this->prix_unitaire > $volumesAppellations[$appellation]['max'])) {
                return true;
            }
        }
        return false;
    }

	public function isPluriannuel() {
        $configuration = $this->getVracConfiguration();
		return ($this->contrat_pluriannuel == 1 && $configuration && $configuration->isContratPluriannuelActif());
	}

	public function isAdossePluriannuel() {
		return ($this->reference_contrat_pluriannuel)? true : false;
	}

    public function getContratPluriannelReferent() {
        return ($this->isAdossePluriannuel())? VracClient::getInstance()->findByNumContrat($this->reference_contrat_pluriannuel) : null;
    }

    public function cleanPluriannuel() {
        $this->pluriannuel_campagne_debut = null;
        $this->pluriannuel_campagne_fin = null;
        $this->contrat_pluriannuel = 0;
        $this->pluriannuel_clause_indexation = null;
    }

    public function prixIsInFourchette() {
        if (
            $this->prix_unitaire > 0 &&
            $this->pluriannuel_prix_plafond > 0 &&
            $this->pluriannuel_prix_plancher > 0 &&
            (($this->prix_unitaire > $this->pluriannuel_prix_plafond)||($this->prix_unitaire < $this->pluriannuel_prix_plancher))
        ) {
            return false;
        } else {
            return true;
        }
    }
}
