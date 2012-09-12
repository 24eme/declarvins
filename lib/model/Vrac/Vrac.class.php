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

    public function update($params = array()) {
      parent::update($params);
	  if ($this->part_cvo > 0) {
	  	$this->prix_total = round($this->prix_unitaire * $this->volume_propose + $this->prix_unitaire * $this->volume_propose * $this->part_cvo * ConfigurationVrac::REPARTITION_CVO_ACHETEUR / 100, 2);
	  } else {
      	$this->prix_total = round($this->prix_unitaire * $this->volume_propose, 2);
	  }
    }

    public function validate() {
      $this->valide->statut = VracClient::STATUS_CONTRAT_NONSOLDE;
      $this->valide->date_saisie = date('Y-m-d');
    }
    
    public function getStatutCssClass() {
    	$statuts = VracClient::getInstance()->getStatusContratCssClass();
    	if ($this->valide->statut && isset($statuts[$this->valide->statut])) {
    		return $statuts[$this->valide->statut];
    	} else {
    		return null;
    	}
    }
}