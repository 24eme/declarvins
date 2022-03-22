<?php
class ConfigurationProduitCepage extends BaseConfigurationProduitCepage 
{
	const TYPE_NOEUD = 'cepage';
	const CODE_APPLICATIF_NOEUD = 'CE';
	
	public function getChildrenNode() 
	{
      return null;
    }

    public function getProduits($onlyForDrmVrac = false, $cvoNeg = false, $date = null)
    {
		if (!$this->isDeclarable()) {
			return array();
		}

    	if ($onlyForDrmVrac) {
    		if (!$this->getCurrentDrmVrac(true)) {
    			return array();
    		}
    	}  
    	     
        if($cvoNeg){
            return array($this->getHash() => $this);
        } 
        
        return $this->getProduitWithTaux($date);
        
    }

    public function getProduitsEnPrestation($interpro) 
    {      
        $prestations = $this->getCurrentPrestations(true);
        if (!$prestations || !in_array($interpro, $prestations)) {
    		return array();
    	}  
        return array($this->getHash() => $this);

    }

    public function getDroitCVO($date = null) {
        return $this->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, $date, true);
    }

	protected function isDeclarable($date = null) {
        if (!$date)
		 	$date = date('Y-m-d');
		$cvo = $this->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, $date, true);
		$douane = $this->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE, $date, true);
		return !($cvo && $douane && $cvo->taux == -1 && $douane == -1);
	}

    protected function getProduitWithTaux($date = null) {
        $date_cvo = (!$date)? date('Y-m-d') : $date;
        $droit = $this->getCurrentDroit(ConfigurationProduit::NOEUD_DROIT_CVO, $date_cvo, true);
        if(!$droit || $droit->taux >= 0.0){
             return array($this->getHash() => $this);
        }
        return array();
    }
    
    
    public function getTreeProduits()
    {
		return array($this->getHash() => $this->getLibelles());
    }
	
	public function getTotalLieux() 
	{
		return array();
	}
	
	public function getTotalCouleurs($onlyForDrmVrac = false, $cvoNeg = false, $date = null, $exception = null) 
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
    
    public function getAllPrestations()
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
    
    public function getLibelleFormat($params = array(), $format = "%g% %a% %l% %co% %ce%") {
        $complete_libelle = "";
        foreach ($this->getLibelles() as $key => $value){
            if($value){
                $complete_libelle.=$value." ";
            }
        }
        return $complete_libelle;
    }
    
    public function getLibelleEdi() {        
        return str_replace(',', '.', trim($this->getLibelleFormat()));
    }
    
    public function getIdentifiantDouane()
    {
    	$inao = $this->getInao();
    	if (!$inao) {
    		return $this->getLibelleFiscal();
    	}
    	return $inao;
    }
    
    public function getCodeDouane()
    {
    	return $this->getIdentifiantDouane();
    }
    
	/*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */
    
  	public function hasLabels() { return false; }
    
  	public function hasDepartements() { return false; }
  	
	public function hasPrestations() { return false; }
  	
  	public function hasCvo() { return false; }
  	
  	public function hasDouane() { return false; }
  	
  	public function hasDRMVrac() { return false; }
  	
  	public function hasCiel() { return true; }
  	  	
  	public function hasOIOC() { return false; }
  	
  	public function hasDefinitionDrm() { return false; }
  	
  	public function getTypeNoeud() { return self::TYPE_NOEUD; }
  	
  	public function getCodeApplicatif() { return self::CODE_APPLICATIF_NOEUD; }
  	
  	public function getCsvLibelle() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CEPAGE_LIBELLE; }
  	
  	public function getCsvCode() { return ConfigurationProduitCsvFile::CSV_PRODUIT_CEPAGE_CODE; }
}