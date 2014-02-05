<?php
class ConfigurationProduitCepage extends BaseConfigurationProduitCepage 
{
	const TYPE_NOEUD = 'cepage';
	const CODE_APPLICATIF_NOEUD = 'CE';
	
	public function getChildrenNode() 
	{
      return null;
    }

    public function getProduits($departements = null, $onlyForDrmVrac = false, $cvoNeg = false, $date = null) 
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
        if($cvoNeg){
            return array($this->getHash() => $this);
        }        
        
    	if ($onlyForDrmVrac) {
    		if (!$this->getCurrentDrmVrac(true)) {
    			return array();
    		}
    	}
        
        return $this->getProduitWithTaux($date);
        
    }
    
    protected function getProduitWithTaux($date = null) {
        $date_cvo = (!$date)? date('Y-m-d') : $date;
        $taux = $this->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, $date_cvo, true)->taux;
        if($taux >= 0.0){
             return array($this->getHash() => $this);
        }
        return array();
    }
    
    
    public function getTreeProduits()
    {
		return array($this->getHash() => $this->getLibelles());
    }
	
	public function getTotalLieux($departements = null) 
	{
		return array();
	}
    
    public function getAllCepages()
    {
    	return array($this->code => $this->libelle);
    }
    
    public function getAllAppellations()
    {
    	return array();
    }
    
    public function getAllCertifications()
    {
    	return array();
    }
    
    public function getAllLabels()
    {
    	return array();
    }
    
    public function getAllDepartements()
    {
    	return array();
    }
    
    public function getAllLieux()
    {
    	return array();
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
        return $this->getCouleur()->getLieu()->getAppellation();
    }

    public function getMention() 
    {
        return $this->getLieu()->getMention();
    }

    public function getLieu() 
    {
        return $this->getCouleur()->getLieu();
    }
    
    public function getCouleur()
    {
    	return $this->getParentNode();
    }
    
    public function getCepage()
    {
    	return $this;
    }

    public function getCertificationLibelle($defaut = true) 
    {
    	$libelle = $this->getCertification()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }

    public function getGenreLibelle($defaut = true) 
    {
        $libelle = $this->getGenre()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }
  	
	public function getAppellationLibelle($defaut = true) 
	{
        $libelle = $this->getAppellation()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }

    public function getMentionLibelle($defaut = true) 
    {
        $libelle = $this->getMention()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }

    public function getLieuLibelle($defaut = true) 
    {
        $libelle = $this->getLieu()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }
    
    public function getCouleurLibelle($defaut = true)
    {
        $libelle = $this->getCouleur()->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }
    
    public function getCepageLibelle($defaut = true)
    {
        $libelle = $this->libelle;
    	return (!$libelle && $defaut)? ConfigurationProduit::DEFAULT_LIBELLE : $libelle;
    }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return false; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE; }
}