<?php
class ConfigurationProduitMention extends BaseConfigurationProduitMention 
{
	const TYPE_NOEUD = 'mention';
	const CODE_APPLICATIF_NOEUD = 'M';
	
	public function getChildrenNode() 
	{
      return $this->lieux;
    }
    
	public function getCertification() 
    {
        return $this->getAppellation()->getCertification();
    }

    public function getGenre() 
    {
        return $this->getAppellation()->getGenre();
    }
  	
	public function getAppellation() 
	{
    	return $this->getParentNode();
    }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
	public function setDonneesCsv($datas) 
    {
    	// On passe le noeud mention (non utilisé à ce jour pour ce projet)
    	$this->setDonneesCsvCallback($datas);
    }
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
	public function hasPrestations() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return null; }
  	
  	public function getCsvCode() { return null; }

}