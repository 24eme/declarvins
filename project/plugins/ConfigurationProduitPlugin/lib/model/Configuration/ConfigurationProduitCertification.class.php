<?php

class ConfigurationProduitCertification extends BaseConfigurationProduitCertification 
{
	const TYPE_NOEUD = 'certification';
	const CODE_APPLICATIF_NOEUD = 'C';
	
	public function getChildrenNode() 
	{
      return $this->genres;
    }
    
    public function getAllCertifications()
    {
    	return array($this->code => $this->libelle);
    }
    
    public function getAllLabels()
    {
    	return $this->getCurrentLabels(true);
    }
    
	public function getLibelles() 
	{
        return array($this->libelle);
    }

    public function getCodes() 
    {
		return array($this->code);
    }
    
    public function callbackCurrentDroit($typeDroit, $atDate = null, $onlyValue = false) { return null; }
    
    public function callbackHistoryDroit($typeDroit, $onlyValue = false) { return array(); }
    
    public function callbackCurrentDepartements($onlyValue = false) { return null; }
    
	public function callbackCurrentPrestations($onlyValue = false) { return null; }
    
    public function callbackCurrentLabels($onlyValue = false) { return null; }
    
    public function callbackCurrentDrmVrac($onlyValue = false) { return null; }
    
    public function callbackCurrentOrganisme($atDate = null, $onlyValue = false) { return null; }
    
	public function callbackHistoryOrganisme($onlyValue = false) { return array(); }
    
    /*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
    protected function setDonneesCsvCallback($datas) { return; }
    
  	public function hasLabels() { return true; }
    
  	public function hasDepartements() { return false; }
  	
	public function hasPrestations() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return true; }
  	
  	public function hasDRMVrac() { return false; }
  	
  	public function hasCiel() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE; }

}
