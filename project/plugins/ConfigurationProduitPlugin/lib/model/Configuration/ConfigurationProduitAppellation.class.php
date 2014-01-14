<?php
class ConfigurationProduitAppellation extends BaseConfigurationProduitAppellation 
{
	const TYPE_NOEUD = 'appellation';
	const CODE_APPLICATIF_NOEUD = 'A';
	
	public function getChildrenNode() 
	{
      return $this->mentions;
    }
    
    public function getAllAppellations()
    {
    	return array($this->code => $this->libelle);
    }

    public function getCertification() 
    {
        return $this->getGenre()->getCertification();
    }

    public function getGenre() 
    {
    	return $this->getParentNode();
    }
    
    public function getAllDepartements()
    {
    	return $this->getCurrentDepartements(true);
    }
    
    /*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return true; }
  	
  	public function hasCvo() { return true; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return true; }
  	  	
  	public function hasOIOC() { return true; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE; }
}
