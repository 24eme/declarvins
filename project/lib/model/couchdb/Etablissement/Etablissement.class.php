<?php
class Etablissement extends BaseEtablissement {
    
    protected $_interpro = null;
    
    /**
     * @return _Compte
     */
    public function getInterproObject() {
        if (is_null($this->_interpro)) {
            $this->_interpro = InterproClient::getInstance()->retrieveDocumentById($this->interpro);
        }
        
        return $this->_interpro;
    }
    
}