<?php
class Vrac extends BaseVrac 
{
    public function constructId() 
    {
        $this->set('_id', 'VRAC-'.$this->numero_contrat);
        if(!$this->date_signature) {
            $this->date_signature = date('d/m/Y');
        }
        if(!$this->date_stats) {
            $this->date_stats = date('d/m/Y');
        }
    }

    public function getProduitObject() 
    {
      return ConfigurationClient::getCurrent()->get($this->produit);
    }
    
    public function getLibelleProduit($format = "%g% %a% %l% %co% %ce%")
    {
    	$produit = $this->getProduitObject();
    	return $produit->getLibelleFormat(array(), $format);
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
      	if ($informations->exist('adresse')) $informations->adresse = $etablissement->siege->adresse;
      	if ($informations->exist('code_postal')) $informations->code_postal = $etablissement->siege->code_postal;
      	if ($informations->exist('commune')) $informations->commune = $etablissement->siege->commune;
      	if ($informations->exist('telephone')) $informations->telephone = $etablissement->telephone;
      	if ($informations->exist('fax')) $informations->fax = $etablissement->fax;
      	if ($informations->exist('email')) $informations->email = $etablissement->email;
    }
    
    public function getCvoUnitaire() {
    	return round($this->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR, 2);
    }
    
    public function getTotalUnitaire() {
    	return round($this->prix_unitaire + $this->getCvoUnitaire(), 2);
    }

    public function update($params = array()) {
      parent::update($params);
	  if ($this->has_cotisation_cvo && $this->part_cvo > 0) {
	  	$this->prix_total = round($this->volume_propose * $this->getTotalUnitaire(), 2);
	  } else {
      	$this->prix_total = round($this->prix_unitaire * $this->volume_propose, 2);
	  }
    }

    public function validate() {
      $this->valide->statut = VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION;
      $this->valide->date_saisie = date('c');
      $acteurs = VracClient::getInstance()->getActeurs();
      if ($this->vous_etes && in_array($this->vous_etes, $acteurs)) {
      	$validateur = 'date_validation_'.$this->vous_etes;
      	$this->valide->{$validateur} = date('c');
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
      }
    }
    
    public function isValide() {
    	return ($this->valide->statut && $this->valide->statut != VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION)? true : false;
    }
    
    public function isModifiable() {
    	return ($this->valide->statut)? false : true;
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
    
    public function hasAdresseLivraison() {
    	return ($this->adresse_livraison->adresse || $this->adresse_livraison->code_postal || $this->adresse_livraison->commune);
    }
    
    public function hasAdresseStockage() {
    	return ($this->adresse_stockage->adresse || $this->adresse_stockage->code_postal || $this->adresse_stockage->commune);
    }
    
    public function integreVolumeEnleve($volume) {
    	$this->volume_enleve = $this->volume_enleve + $volume;
    }
}