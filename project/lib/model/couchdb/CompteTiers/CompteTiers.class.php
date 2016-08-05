<?php

class CompteTiers extends BaseCompteTiers {
    
    protected $_contrat = null;
    

    
	protected function preSave() {
        if ($this->isNew()) {
        	$this->acces->add(null, acVinCompteSecurityUser::CREDENTIAL_ACCES_PLATERFORME);
        }
	    parent::preSave();
    }
    
    public function getNbEtablissementByInterproId() 
    {
        $result = array();
        foreach ($this->getTiers() as $tier) {
            if (array_key_exists($tier->getInterpro(), $result)) {
                $result[$tier->getInterpro()] = $result[$tier->getInterpro()] + 1;
            }
            else {
                $result[$tier->getInterpro()] = 1;
            }
        }
        return $result;
    }
    
    /**
     * @return _Compte
     */
    public function getContratObject() {
        if (is_null($this->_contrat)) {
            $this->_contrat = ContratClient::getInstance()->retrieveDocumentById($this->contrat);
        }
        
        return $this->_contrat;
    }
    
    /**
     * @return _Compte
     */
    public function getConventionCiel() {
        return ConventionCielClient::getInstance()->retrieveById($this->login);
    }
    
    public function getTiersCollection() {
        return acCouchdbManager::getClient()->keys(array_keys($this->getTiers()->toArray()))->execute();
    }

    public function addEtablissement($etablissement) {
	$compte_tiers = $this->addTiers($etablissement);
	$compte_tiers->interpro = $etablissement->interpro;
	return $compte_tiers;
    }

    public function hasEtablissementId($etablissementId) {
      return $this->tiers->exist('ETABLISSEMENT-'.$etablissementId);
    }

    public function hasEtablissement($identifiant) {
      return $this->tiers->exist($identifiant);
    }
    
    public function generateByContrat($contrat) {
    	$this->_id = 'COMPTE-'.$contrat->no_contrat;
    	$this->contrat = $contrat->_id;
    	$this->nom = $contrat->nom;
    	$this->prenom = $contrat->prenom;
    	$this->fonction = $contrat->fonction;
    	$this->telephone = $contrat->telephone;
    	$this->fax = $contrat->fax;
    	$this->email = $contrat->email;
    	$etablissement = $contrat->etablissements->getFirst();
    	$this->raison_sociale = $etablissement->raison_sociale;
    	$this->dematerialise_ciel = $contrat->dematerialise_ciel;
    }

    public function __toString() {
        if ($this->prenom) {
            return sprintf('%s. %s', $this->prenom, strtoupper(substr($this->nom, 0, 1)));
        }
        
        return sprintf('%s', $this->nom);
    }
    
    public function isVirtuel() {
    	return false;
    }
    
    public function isTiers() {
    	return true;
    }

    private function cleanPhone($phone) {
        $phone = preg_replace('/[^0-9\+]+/', '', $phone);
        $phone = preg_replace('/^00/', '+', $phone);
        $phone = preg_replace('/^0/', '+33', $phone);

        if (strlen($phone) == 9 && preg_match('/^[64]/', $phone) )
        	$phone = '+33'.$phone;

        if (!preg_match('/^\+/', $phone) || (strlen($phone) != 12 && preg_match('/^\+33/', $phone)))
        	echo("$phone n'est pas un téléphone correct\n");
        
        return $phone;
    }

    public function setFax($fax) {
        if ($fax)
            $this->_set('fax', $this->cleanPhone($fax));
    }
    public function setTelephone($phone) {
        if ($phone)
            $this->_set('telephone', $this->cleanPhone($phone));
    }
}
