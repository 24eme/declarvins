<?php
/**
 * Model for Configuration
 *
 */

class ConfigurationProduit extends BaseConfigurationProduit
{

  	const DEFAULT_KEY = 'DEFAUT';
  	const DEFAULT_LIBELLE = 'Défaut';
  	const CERTIFICATION_AOP = 'AOP';
  	const CERTIFICATION_IGP = 'IGP';
  	const CERTIFICATION_VINSSANSIG = 'VINSSANSIG';
  	const CERTIFICATION_MOUTS = 'MOUTS';
  	const CLE_COULEUR_ROUGE = 1;
  	const CODE_COULEUR_ROUGE = 'rouge';
  	const LIBELLE_COULEUR_ROUGE = 'Rouge';
  	const CLE_COULEUR_ROSE = 2;
  	const CODE_COULEUR_ROSE = 'rose';
  	const LIBELLE_COULEUR_ROSE = 'Rosé';
  	const CLE_COULEUR_BLANC = 3;
  	const CODE_COULEUR_BLANC = 'blanc';
  	const LIBELLE_COULEUR_BLANC = 'Blanc';
  	const NOEUD_DROIT_CVO = 'cvo';
  	const NOEUD_DROIT_DOUANE = 'douane';
  	
  	protected static $correspondance_couleurs = array (
		self::CLE_COULEUR_ROUGE => self::CODE_COULEUR_ROUGE,
		self::CLE_COULEUR_ROSE => self::CODE_COULEUR_ROSE,
		self::CLE_COULEUR_BLANC => self::CODE_COULEUR_BLANC
	);
	
	protected static $arborescence = array(
		'certifications', 
		'genres', 
		'appellations', 
		'mentions', 
		'lieux', 
		'couleurs', 
		'cepages'
	);
	
	protected static $correspondance_noeuds = array(
		'certifications' => 'certification', 
		'genres' => 'genre', 
		'appellations' => 'appellation', 
		'lieux' => 'lieu', 
		'couleurs' => 'couleur', 
		'cepages' => 'cepage'
	); 
	
	protected static $correspondance_code_couleurs = array (
		self::LIBELLE_COULEUR_ROUGE => self::CLE_COULEUR_ROUGE,
		self::LIBELLE_COULEUR_ROSE => self::CLE_COULEUR_ROSE,
		self::LIBELLE_COULEUR_BLANC => self::CLE_COULEUR_BLANC 
	);

    public static function getCorrespondanceCouleurs() 
    {
		return self::$correspondance_couleurs;
    }

    public static function getArborescence() 
    {
		return self::$arborescence;
    }

    public static function getCorrespondanceNoeuds() 
    {
		return self::$correspondance_noeuds;
    }
    
    public static function getCorrespondanceCodeCouleurs() 
    {
    	return self::$correspondance_code_couleurs;
    }

    public function constructId() 
    {
        $this->set('_id', ConfigurationProduitClient::getInstance()->buildId($this->interpro));
    }
    
    public function getAppellations()
    {
    	return $this->declaration->getAllAppellations();
    }
    
    public function getLieux()
    {
    	return $this->declaration->getAllLieux();
    }
    
    public function getCepages()
    {
    	return $this->declaration->getAllCepages();
    }
    
    public function getCertifications()
    {
    	return $this->declaration->getAllCertifications();
    }
    
    public function getLabels($hash = null)
    {
    	if ($hash) {
    		if ($this->exist($hash)) {
    			return $this->get($hash)->getAllLabels();
    		}
    		return array();
    	} else {
    		return $this->declaration->getAllLabels();
    	}
    }
    
    public function getDepartements()
    {
    	return array_values(array_unique($this->declaration->getAllDepartements()));
    }
    
    public function getProduits($hash = null, $departements = null, $onlyForDrmVrac = false)
    {
    	if ($hash) {
    		if ($this->exist($hash)) {
    			return $this->get($hash)->getProduits($departements, $onlyForDrmVrac);
    		}
    		return array();
    	}
    	return $this->declaration->getProduits($departements, $onlyForDrmVrac);
    }
    
    public function getTotalLieux($hash = null, $departements = null)
    {
    	if ($hash) {
    		if ($this->exist($hash)) {
    			return $this->get($hash)->getTotalLieux($departements);
    		}
    		return array();
    	}
    	return $this->declaration->getTotalLieux($departements);
    }
    
    public function getInterproObject()
    {
    	return InterproClient::getInstance()->find($this->interpro);
    }
    
    protected function doSave() 
    {
        $interpro = $this->getInterproObject();
        $interpro->departements = $this->getDepartements();
        $interpro->save();
    }
}

