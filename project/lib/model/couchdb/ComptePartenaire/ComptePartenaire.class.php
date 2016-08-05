<?php
/**
 * Model for ComptePartenaire
 *
 */

class ComptePartenaire extends BaseComptePartenaire {
    const COMPTE_TYPE_PARTENAIRE = 'ComptePartenaire';
    
    public function isVirtuel() {
    	return false;
    }
    
    public function isTiers() {
    	return false;
    }
}
