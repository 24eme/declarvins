<?php
/**
 * Model for ConfigurationVrac
 *
 */

class ConfigurationVrac extends BaseConfigurationVrac {
	
	const FAMILLE_VENDEUR = 'Viticulteur';
	const FAMILLE_ACHETEUR = 'Négociant';
	const FAMILLE_MANDATAIRE = 'Courtier';
	
	/*
     * @todo 
     */
    public function getVendeurs() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_VENDEUR)->rows;
    }
    /*
     * @todo 
     */
    public function getAcheteurs() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_ACHETEUR)->rows;
    }
    /*
     * @todo 
     */
    public function getMandataires() {
    	return EtablissementAllView::getInstance()->findByInterproAndFamille($this->getKey(), self::FAMILLE_MANDATAIRE)->rows;
    }
    /*
     * @todo 
     */
    public function getMandatants() {
    	return array();
    }
    /*
     * @todo 
     */
    public function getConfig() {
    	return $this->getDocument();
    }

}