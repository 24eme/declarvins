<?php
class ConfigurationProduitDeclaration extends BaseConfigurationProduitDeclaration 
{
    const TYPE_NOEUD = 'declaration';

    public function getChildrenNode() 
    {
        return $this->certifications;
    }
    
    public function getCurrentDroit($typeDroit, $atDate = null) { return null; }
    
	public function getHistoryDroit($typeDroit) { return null; }
    
    public function getCurrentDepartements() { return null; }
    
    public function getCurrentDrmVrac() { return null; }
    
    public function getCurrentOrganisme($atDate = null) { return null; }
    
	public function getHistoryOrganisme() { return null; }
    
    public function getCurrentLabels() { return null; }
    
    public function getCurrentDefinitionDrm() { return null; }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
	public function setDonneesCsv($datas) {}

  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
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