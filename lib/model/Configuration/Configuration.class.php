<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration 
{

  	const DEFAULT_KEY = 'DEFAUT';
  	const CERTIFICATION_AOP = 'AOP';
  	const CERTIFICATION_IGP = 'IGP';
  	const CERTIFICATION_VINSSANSIG = 'VINSSANSIG';
  	const CERTIFICATION_MOUTS = 'MOUTS';

    protected $produits_libelle = null;
    protected $produits_code = null;
    protected $format_produits = null;

    public function loadAllData() {
      parent::loadAllData();
      //$this->loadProduits();
    }

    protected function loadProduits() {
      $this->getProduits();
      $this->getProduitsLibelles();
      $this->getProduitsCodes();
      //$this->getProduitLibelleByHash();
      //$this->getProduitCodeByHash();
    }

    public function constructId() 
    {
        $this->set('_id', "CONFIGURATION");
    }
    
    /*
     * NEW 
     */
    public function getCertifications()
    {
    	$certifications = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		$certifications = array_merge($certifications, $configurationProduits->getCertifications());
    	}
    	return $certifications;
    }
    
    public function getConfigurationProduits($interpro)
    {
    	return ($this->produits->exist($interpro))? acCouchdbManager::getClient()->retrieveDocumentById($this->produits->get($interpro)) : null;
    }
    
    public function getConfigurationProduitsComplete()
    {
    	$configuration = array();
    	foreach ($this->produits->toArray() as $interpro => $configurationProduits) {
    		$configuration[$interpro] = $this->getConfigurationProduits($interpro);
    	}
    	return $configuration;
    }

    public function formatProduits()
    {
    	$produits = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		$certifications = array_merge($certifications, $configurationProduits->getCertifications());
    	}
    	return $certifications;
    }

    public function save() {
        parent::save();
        ConfigurationClient::getInstance()->cacheResetCurrent();
    }

    public function prepareCache() {
      $this->loadAllData();
    }
}

