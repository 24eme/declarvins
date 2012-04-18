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
  	public function hasLabels() {
  		return false;
  	}
  	public function hasDetails() {
  		return true;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
  	
  	public function getDetailConfiguration() {
  		$details = $this->getAppellation()->getDetailConfiguration();
  		if ($this->exist('detail')) {
  			foreach ($this->detail as $type => $detail) {
  				foreach ($detail as $noeud => $droits) {
  					if ($droits->readable !== null)
  						$details->get($type)->get($noeud)->readable = $droits->readable;
  					if ($droits->writable !== null)
  						$details->get($type)->get($noeud)->writable = $droits->writable;
  				}
  			}
  		}
  		return $details;
  	}
}