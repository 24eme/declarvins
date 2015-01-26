<?php
class ConfigurationProduitDeclaration extends BaseConfigurationProduitDeclaration 
{
    const TYPE_NOEUD = 'declaration';

    public function getChildrenNode() 
    {
        return $this->certifications;
    }
    
    public function getCurrentDroit($typeDroit, $atDate = null, $onlyValue = false) { return null; }
    
	public function getHistoryDroit($typeDroit, $onlyValue = false) { return null; }
    
    public function getCurrentDepartements($onlyValue = false) { return null; }
    
	public function getCurrentPrestations($onlyValue = false) { return null; }
    
    public function getCurrentDrmVrac($onlyValue = false) { return null; }
    
    public function getCurrentOrganisme($atDate = null, $onlyValue = false) { return null; }
    
	public function getHistoryOrganisme($onlyValue = false) { return null; }
    
    public function getCurrentLabels($onlyValue = false) { return null; }
    
    public function getCurrentDefinitionDrm($onlyValue = false) { return null; }
    
	public function getLibelles() { return null; }

    public function getCodes() { return null; }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
	public function setDonneesCsv($datas) {}

  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
	public function hasPrestations() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return null; }
  	
  	public function getCsvLibelle() { return null; }
  	
  	public function getCsvCode() { return null; }

}