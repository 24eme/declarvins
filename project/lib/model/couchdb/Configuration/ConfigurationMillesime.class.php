<?php
/**
 * Model for ConfigurationMillesime
 *
 */

class ConfigurationMillesime extends BaseConfigurationMillesime {
	
	const TYPE_NOEUD = 'millesime';

	protected function loadAllData() {
		parent::loadAllData();
		$this->getLibelles();
    }

    public function getCertification() {
    	return $this->getAppellation()->getCertification();
    }

    public function getAppellation() {
    	return $this->getLieu()->getAppellation();
    }

    public function getLieu() {
    	return $this->getCouleur()->getLieu();
    }

    public function getCouleur() {
    	return $this->getCepage()->getCouleur();
    }

    public function getCepage() {
    	return $this->getParentNode();
    }

    public function getMillesime() {
    	return $this;
    }
    
    public function setDonneesCsv($datas) {
    	$this->getCepage()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_MILLESIME_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_MILLESIME_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_MILLESIME_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_MILLESIME_CODE] : null;
    }
    
  	public function hasDepartements() {
  		return false;
  	}
  	public function hasDroits() {
  		return false;
  	}
  	public function hasLabels() {
  		return false;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}