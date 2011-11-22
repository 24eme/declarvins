<?php

class CompteTiers extends BaseCompteTiers {
    
    protected $_contrat = null;
    
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