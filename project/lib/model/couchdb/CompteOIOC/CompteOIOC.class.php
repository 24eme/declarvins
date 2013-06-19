<?php
/**
 * Model for ComptePartenaire
 *
 */

class CompteOIOC extends BaseCompteOIOC {
	
    const COMPTE_TYPE_OIOC = 'CompteOIOC';
    
    public function isVirtuel() {
    	return false;
    }
    
	protected function preSave() {
        if ($this->isNew()) {
        	$this->acces->add(null, acVinCompteSecurityUser::CREDENTIAL_ACCES_EDI);
        }
	    parent::preSave();
    }
}
