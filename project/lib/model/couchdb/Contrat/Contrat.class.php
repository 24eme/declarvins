<?php
class Contrat extends BaseContrat {
    
    protected $_compte = null;
    
    /**
     * @return _Compte
     */
    public function getCompteObject() {
        if (is_null($this->_compte)) {
            $this->_compte = acCouchdbManager::getClient('_Compte')->retrieveDocumentById($this->compte);
        }
        return $this->_compte;
    }
    
    public function getDepartementsEtablissements()
    {
    	$departements = array();
    	foreach ($this->etablissements as $etablissement) {
    		$departements[] = substr($etablissement->code_postal, 0, 2);
    	}
    	return $departements;
    }
}