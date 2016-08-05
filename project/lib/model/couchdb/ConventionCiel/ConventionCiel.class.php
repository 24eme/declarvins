<?php
/**
 * Model for ConventionCiel
 *
 */

class ConventionCiel extends BaseConventionCiel {
    
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
    
    public function getInterprofession() 
    {
    	if ($this->interpro == 'INTERPRO-IR') {
    		return 'InterRhône';
    	}
    	if ($this->interpro == 'INTERPRO-CIVP') {
    		return 'CIVP';
    	}
    	return $this->interpro;
    }

}