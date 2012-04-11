<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {
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
}