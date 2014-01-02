<?php
class ConfigurationProduitGenre extends BaseConfigurationProduitGenre 
{
	const TYPE_NOEUD = 'genre';
	const CODE_APPLICATIF_NOEUD = 'G';
	
	public function getChildrenNode() 
	{
      return $this->appellations;
    }

    public function getCertification() 
    {
    	return $this->getParentNode();
    }
    
    /*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return true; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_GENRE_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_GENRE_CODE; }
}