<?php
class Contrat extends BaseContrat {
    
    protected $_compte = null;
    
    /**
     * @return _Compte
     */
    public function getCompteObject() {
        if (is_null($this->_compte)) {
            $this->_compte = _CompteClient::getInstance()->retrieveDocumentById($this->compte);
        }
        
        return $this->_compte;
    }
}