<?php

class ConfigurationProduitCertification extends BaseConfigurationProduitCertification 
{
	const TYPE_NOEUD = 'certification';
	const CODE_APPLICATIF_NOEUD = 'C';
	
	public function getChildrenNode() 
	{
      return $this->genres;
    }
    
    public function callbackCurrentDroit($typeDroit, $atDate = null) { return null; }
    
    public function callbackHistoryDroit($typeDroit) { return array(); }
    
    public function callbackCurrentDepartements() { return null; }
    
    public function callbackCurrentLabels() { return null; }
    
    public function callbackCurrentDrmVrac() { return null; }
    
    public function callbackCurrentOrganisme($atDate = null) { return null; }
    
	public function callbackHistoryOrganisme() { return array(); }
    
    /*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
    protected function setDonneesCsvCallback($datas) { return; }
    
  	public function hasLabels() { return true; }
    
  	public function hasDepartements() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return true; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE; }

}
