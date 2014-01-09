<?php
class Vrac extends BaseVrac 
{
	const MODE_DE_SAISIE_PAPIER = 'PAPIER';
    const MODE_DE_SAISIE_DTI = 'DTI';
    const MODE_DE_SAISIE_EDI = 'EDI';
    
    protected static $_mode_de_saisie_libelles = array (
    									self::MODE_DE_SAISIE_PAPIER => 'par l\'interprofession (papier)',
    									self::MODE_DE_SAISIE_DTI => 'via Declarvins (DTI)',
    									self::MODE_DE_SAISIE_EDI => 'via votre logiciel (EDI)');
    
    public function constructId() 
    {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
    }

    public function getProduitObject() 
    {
        $configuration = ConfigurationClient::getCurrent();
        return $configuration->getConfigurationProduit($this->produit);
    }
    
    public function getLibelleProduit($format = "%g% %a% %l% %co% %ce%")
    {
    	if ($this->produit_libelle) {
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
    	if ($compte = $etablissement->getCompteObject()) {
    		return ($compte->statut == _Compte::STATUT_INSCRIT);
    	}
    	return false;
    }
    
    public function acheteurHasCompteActif()
    {
    	$etablissement = $this->getAcheteurObject();
    	if ($compte = $etablissement->getCompteObject()) {
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
    
    public function getVendeurInterpro() 
    {
        return $this->getVendeurObject()->interpro;
    }

    public function getProduitInterpro() 
    {
    	if ($this->produit) {
    		$produit = $this->getProduitObject();
      		return $produit->getDocument()->getInterproObject();
    	}
    	return null;
    }

    public function storeSoussignesInformations() {
      $this->storeSoussigneInformations('acheteur', $this->getAcheteurObject());
      $this->storeSoussigneInformations('vendeur', $this->getVendeurObject());
      $this->storeSoussigneInformations('mandataire', $this->getMandataireObject());
    }

    public function storeSoussigneInformations($type, $etablissement) 
    {        
    	   $informations = $this->get($type);

         if(!$etablissement) {

          return null;
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
    }
    
    public function getCvoUnitaire() {
    	return round($this->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2);
    }
    
    public function getTotalUnitaire() {
    	return round($this->prix_unitaire + $this->getCvoUnitaire(), 2);
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
    }

    public function update($params = array()) {
      parent::update($params);
      $this->prix_total_net = round($this->prix_unitaire * $this->volume_propose, 2);
	  if ($this->has_cotisation_cvo && $this->part_cvo > 0) {
	  	$this->prix_total = round($this->volume_propose * $this->getTotalUnitaire(), 2);
	  } else {
      	$this->prix_total = round($this->prix_unitaire * $this->volume_propose, 2);
	  }
    }
    
    public function normalizeNumeric()
    {
    	$this->prix_unitaire = ($this->prix_unitaire)? $this->prix_unitaire * 1 : 0;
    	$this->prix_total = ($this->prix_total)? $this->prix_total * 1 : 0;
    	$this->prix_total_net = ($this->prix_total_net)? $this->prix_total_net * 1 : 0;
    	$this->volume_propose = ($this->volume_propose)? $this->volume_propose * 1 : 0;
    	$this->volume_enleve = ($this->volume_enleve)? $this->volume_enleve * 1 : 0;
    }

    public function validate($user) {
    	$this->valide->statut = VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION;
    	$this->valide->date_saisie = date('c');
    	$this->valide->identifiant = $user->getCompte()->_id;
    	$acteurs = VracClient::getInstance()->getActeurs();
      	if (!$this->mandataire_exist) {
      		unset($acteurs[array_search(VracClient::VRAC_TYPE_COURTIER, $acteurs)]);
      	}
    	if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		$this->mode_de_saisie = self::MODE_DE_SAISIE_PAPIER;
    		foreach ($acteurs as $acteur) {
    			$validateur = 'date_validation_'.$acteur;
    			if (!$this->valide->get($validateur)) {
    				$this->valide->{$validateur} = date('c');
    			}
    		}
    		$this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
    		$this->valide->date_validation = date('c');
    	} else {
    		$this->mode_de_saisie = self::MODE_DE_SAISIE_DTI;
    		if ($this->vous_etes && in_array($this->vous_etes, $acteurs)) {
    			$validateur = 'date_validation_'.$this->vous_etes;
    			$this->valide->{$validateur} = date('c');
    		}
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
      	$this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
    	$this->valide->date_validation = date('c');
      }
    }
    


    protected function updateStatutSolde() {
        if ($this->volume_propose > 0 && $this->volume_enleve >= $this->volume_propose && $this->valide->statut != VracClient::STATUS_CONTRAT_SOLDE) {
        	$this->valide->statut = VracClient::STATUS_CONTRAT_SOLDE;
        }
	    $this->normalizeNumeric();
    }
    
    public function save($updateStatutSolde = true) {
    	if ($updateStatutSolde) {
    		$this->updateStatutSolde();
    	}
    	parent::save();
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
    	return ($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION)? true : false;
    }

    public function isModifiable() {
      if($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {
        return false;
      }

    	return !($this->volume_enleve > 0);
    }

    public function isVisualisable() {
      return ($this->valide->statut)? true : false;
    }
    
    public function getStatutCssClass() {
    	$statuts = VracClient::getInstance()->getStatusContratCssClass();
    	if ($this->valide->statut && isset($statuts[$this->valide->statut])) {
    		return $statuts[$this->valide->statut];
    	} else {
    		return null;
    	}
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
}