<?php
/**
 * Model for ConfigurationAppellation
 *
 */

class ConfigurationAppellation extends BaseConfigurationAppellation {
	
	const TYPE_NOEUD = 'appellation';

    public function getGenre() {

      return $this->getParentNode();
    }

    public function getCertification() {

        return $this->getGenre()->getCertification();
    }

    public function getLibelle() {
      $libelle = $this->_get('libelle');
      if ($libelle)
	     return $libelle;
      return 'Total';
    }
    
    public function setDonneesCsv($datas) {
    	$this->getGenre()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE] : null;
    	if (ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD]) {
    		$this->setDroitDouaneCsv($datas);
    	}
    	if (ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {
    		$this->setDroitCvoCsv($datas);
    	}
    }
    
  	public function hasDepartements() {
  		return true;
  	}
  	public function hasDroits() {
  		return true;
  	}
  	public function hasLabels() {
  		return true;
  	}
  	public function hasDetails() {
  		return true;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}
