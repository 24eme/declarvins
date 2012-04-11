<?php
/**
 * Model for ConfigurationCouleur
 *
 */

class ConfigurationCouleur extends BaseConfigurationCouleur {
    
    /**
     *
     * @return ConfigurationLieu
     */
    public function getLieu() {
        return $this->getParentNode();
    }

    public function hasCepage() {
    	return (count($this->cepages) > 1 || (count($this->cepages) == 1 && $this->cepages->getFirst()->getKey() != Configuration::DEFAULT_KEY));
    }

    public function hasMillesime() {
    	foreach($this->cepages as $cepage) {
    		if ($cepage->hasMillesime()) {
    			return true;
    		}
    	}

    	return false;
    }
    
    public function setDonneesCsv($datas) {
    	$this->getLieu()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_COULEUR_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_COULEUR_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_COULEUR_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_COULEUR_CODE] : null;
    }
}