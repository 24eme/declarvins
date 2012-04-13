<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {
	
	const TYPE_NOEUD = 'lieu';
	/**
     *
     * @return ConfigurationAppellation
     */
    public function getAppellation() {
        return $this->getParentNode();
    }
    
    public function setDonneesCsv($datas) {
    	$this->getAppellation()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_LIEU_CODE] : null;
    	$this->departements = ($datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS])? explode(',', $datas[ProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]) : array();
    }
    
  	public function hasDepartements() {
  		return true;
  	}
  	public function hasDroits() {
  		return false;
  	}
  	public function hasLabel() {
  		return false;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}