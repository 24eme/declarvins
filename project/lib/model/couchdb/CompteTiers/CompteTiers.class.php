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
    
}