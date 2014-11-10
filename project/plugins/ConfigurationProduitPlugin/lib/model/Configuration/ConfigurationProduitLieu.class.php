<?php
class ConfigurationProduitLieu extends BaseConfigurationProduitLieu 
{
	const TYPE_NOEUD = 'lieu';
	const CODE_APPLICATIF_NOEUD = 'L';
	
	public function getChildrenNode() 
	{
      return $this->couleurs;
    }
    
    public function getAllLieux()
    {
    	return array($this->code => $this->libelle);
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
        return $this->getMention()->getAppellation();
    }

    public function getMention() 
    {
    	return $this->getParentNode();
    }
    
    public function hasCepage()
    {
    	foreach($this->couleurs as $couleur) {
            if ($couleur->hasCepage()) {
                return true;
            }
        }
        return false;
    }
    
    public function getTotalLieux() 
    {
    	if ($departements) {
    		if (!is_array($departements)) {
    			$departements = array($departements);
    		}
    		if ($currentDepartements = $this->getCurrentDepartements(true)) {
    			$found = false;
    			foreach ($departements as $departement) {
    				if (in_array($departement, $currentDepartements)) {
    					$found = true;
    					break;
    				}
    			}
    			if (!$found) {
    				return array();
    			} 
    		} else {
    			return array();
    		}
    	}
        return array($this->getHash() => $this);
    }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
  	public function hasCvo() { return true; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return true; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_LIEU_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_LIEU_CODE; }
}