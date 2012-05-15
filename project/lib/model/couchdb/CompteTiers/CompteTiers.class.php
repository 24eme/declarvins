<?php

class CompteTiers extends BaseCompteTiers {
    
    protected $_contrat = null;
    
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
    
    public function generateByContrat($contrat) {
    	$this->_id = 'COMPTE-'.$contrat->no_contrat;
    	$this->contrat = $contrat->_id;
    	$this->nom = $contrat->nom;
    	$this->prenom = $contrat->prenom;
    	$this->fonction = $contrat->fonction;
    	$this->telephone = $contrat->telephone;
    	$this->fax = $contrat->fax;
    	$this->email = $contrat->email;
    }

}
