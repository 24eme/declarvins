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
    
    public function isTiers() {
    	return false;
    }
}
