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
  	
  	public $interpro_object = null;
  	
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
		self::CODE_COULEUR_ROUGE => self::CLE_COULEUR_ROUGE,
		self::CODE_COULEUR_ROSE => self::CLE_COULEUR_ROSE,
		self::CODE_COULEUR_BLANC => self::CLE_COULEUR_BLANC 
	);
	
	protected static $correspondance_libelle_couleurs = array (
		self::CODE_COULEUR_ROUGE => self::LIBELLE_COULEUR_ROUGE,
		self::CODE_COULEUR_ROSE => self::LIBELLE_COULEUR_ROSE,
		self::CODE_COULEUR_BLANC => self::LIBELLE_COULEUR_BLANC 
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
    
    public static function getCorrespondanceLibelleCouleurs() 
    {
    	return self::$correspondance_libelle_couleurs;
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
    
    public function getPrestations()
    {
    	return array_values(array_unique($this->declaration->getAllPrestations()));
    }
    
    public function getProduitsEnPrestation($interpro)
    {
    	return $this->declaration->getProduitsEnPrestation($interpro);
    }
    
    public function getProduits($hash = null, $onlyForDrmVrac = false, $cvoNeg = false, $date = null, $avecPrestation = false)
    {
    	if ($hash) {
    		if ($this->exist($hash)) {
    			return ($avecPrestation)? array_merge($this->get($hash)->getProduits($onlyForDrmVrac, $cvoNeg, $date), $this->getProduitsPrestataire($hash)) : $this->get($hash)->getProduits($onlyForDrmVrac, $cvoNeg, $date);
    		}
    		return ($avecPrestation)? $this->getProduitsPrestataire($hash) : array();
    	}
    	return ($avecPrestation)? array_merge($this->declaration->getProduits($onlyForDrmVrac, $cvoNeg, $date), $this->getProduitsPrestataire()) : $this->declaration->getProduits($onlyForDrmVrac, $cvoNeg, $date);
    }
    
    public function getProduitsPrestataire($hash = null)
    {
    	$prestations = $this->getOrAdd('prestations');
    	$produits = array();
    	foreach ($prestations as $i => $val) {
    		if($interpro = InterproClient::getInstance()->find($i)) {
    			if ($configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'))) {
	    				foreach ($val as $key => $value) {
	    					if ($hash && !preg_match("/^".str_replace('/', '_', $hash)."/", $key)) {
	    						continue;
	    					}
	    					if ($configurationProduits->exist($value->lien)) {
	    						$produits = array_merge($produits, $configurationProduits->get($value->lien)->getProduits());
	    					}
    				}
    			}
    		}
    	}
    	return $produits;
    }
    
    public function isProduitInPrestation($hash)
    {
    	$hash = str_replace('/', '_', $hash);
    	$prestations = $this->getOrAdd('prestations');
    	$find = false;
    	foreach ($prestations as $i => $val) {
    		if ($prestations->get($i)->exist($hash)) {
    				$find = true;
    				break;
    		}
    	}
    	return $find;
    }
    
    public function getTreeProduits()
    {
    	return $this->declaration->getTreeProduits();
    }
    
    public function getTotalLieux($hash = null, $avecPrestation = false)
    {
    	if ($hash) {
    		if ($this->exist($hash)) {
    			return ($avecPrestation)? array_merge($this->get($hash)->getTotalLieux(), $this->getTotalLieuxPrestataire($hash)) : $this->get($hash)->getTotalLieux();
    		}
    		return ($avecPrestation)? $this->getTotalLieuxPrestataire($hash) : array();
    	}
    	return ($avecPrestation)? array_merge($this->declaration->getTotalLieux(), $this->getTotalLieuxPrestataire()) : $this->declaration->getTotalLieux();
    }
    

    
    public function getTotalLieuxPrestataire($hash = null)
    {
    	$prestations = $this->getOrAdd('prestations');
    	$produits = array();
    	foreach ($prestations as $i => $val) {
    		if($interpro = InterproClient::getInstance()->find($i)) {
    			if ($configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'))) {
	    				foreach ($val as $key => $value) {
	    					if ($hash && !preg_match("/^".str_replace('/', '_', $hash)."/", $key)) {
	    						continue;
	    					}
	    					if ($configurationProduits->exist($value->lien)) {
	    						$produits = array_merge($produits, $configurationProduits->get($value->lien)->getLieu()->getTotalLieux());
	    					}
    				}
    			}
    		}
    	}
    	return $produits;
    }
    
    public function getInterproObject()
    {
    	if (is_null($this->interpro_object)) {
    		$this->interpro_object = InterproClient::getInstance()->find($this->interpro);
    	}
    	return $this->interpro_object;
    }
    
    protected function doSave() 
    {
        $interpro = $this->getInterproObject();
        $departements = $this->getDepartements();
        $interpro->departements = ($departements)? $departements : array();
        $interpro->save();
    }

    public function save($prestation = false) 
    {
    	if ($prestation) {
    		$this->updatePrestations();
    	}
        parent::save();
        ConfigurationClient::getInstance()->cacheResetCurrent();
    }
    
    public function updatePrestations() 
    {
    	$interpro = $this->getInterproObject();
    	$interpros = InterproClient::getInstance()->getAllInterpros();
    	foreach ($interpros as $inter) {
    		if ($produits = $this->getProduitsEnPrestation($inter)) {
    			if ($obj = InterproClient::getInstance()->find($inter)) {
    				if ($obj->exist('configuration_produits')) {
    					if ($confProduits = ConfigurationProduitClient::getInstance()->find($obj->configuration_produits)) {
    						$prestations = $confProduits->getOrAdd('prestations');
    						if ($prestations->exist($interpro->_id)) {
    							$prestations->remove($interpro->_id);
    						}
    						$prestation = $prestations->add($interpro->_id);
    						foreach ($produits as $hash => $confProduit) {
    							$produit = $prestation->add(str_replace('/', '_', $hash));
    							$produit->lien = $hash;
    							$produit->libelle = ConfigurationProduitClient::getInstance()->format($confProduit->getLibelles());
    							$produit->configuration = $this->_id;
    						}
    						$confProduits->save();
    					}
    				}
    			}
    		}
    	}
    }
}

