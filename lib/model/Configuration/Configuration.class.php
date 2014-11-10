<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration 
{
    protected $_configuration_produits_IR = null;
    protected $_configuration_produits_CIVP = null;
    protected $_configuration_produits_IVSE = null;
    protected $_configuration_produits_ANIVIN = null;
    protected $_configuration_produits_CIVL = null;
    protected $_configuration_produits_IO = null;
    protected $_zones = null;
    
    protected static $stocks_debut = array(
    	'bloque' => 'Dont Vin bloqué / Reserve',
    	'warrante' => 'Dont Vin warranté',
    	'instance' => 'Dont Vin en instance'
    );
    
    protected static $stocks_entree = array(
    	'achat' => 'Achats',
    	'recolte' => 'Récolte',
    	'repli' => 'Replis / Changement de dénomination',
    	'declassement' => 'Déclassement / Lies',
    	'mouvement' => 'Transfert de chai / Embouteillage / Retours',
    	'crd' => 'Réintégration conditionné / Vrac'
    );
    
    protected static $stocks_sortie = array(
    	'vrac' => 'Vrac DAA / DAE / DAC',
    	'export' => 'Conditionné export DAE ou CRD',
    	'factures' => 'DSA / Tickets / Factures',
    	'crd' => 'CRD France',
    	'consommation' => 'Conso Fam. / Analyses / Dégustation',
    	'pertes' => 'Pertes exceptionnelles',
    	'declassement' => 'Non rev. / Déclassement',
    	'repli' => 'Changement / Repli',
    	'mouvement' => 'Transfert de chai / Embouteillage / Prise de mousse',
    	'distillation' => 'Distillation / Destruction',
    	'lies' => 'Lies'
    );
    
    protected static $stocks_fin = array(
    	'bloque' => 'Dont Vin bloqué / Reserve',
    	'warrante' => 'Dont Vin warranté',
    	'instance' => 'Dont Vin en instance',
    	'commercialisable' => 'Dont commercialisable'
    );
    
    public static function getStocksDebut()
    {
    	return self::$stocks_debut;
    }
    
    public static function getStocksEntree()
    {
    	return self::$stocks_entree;
    }
    
    public static function getStocksSortie()
    {
    	return self::$stocks_sortie;
    }
    
    public static function getStocksFin()
    {
    	return self::$stocks_fin;
    }

    public function loadAllData() 
    {
      parent::loadAllData();
      $this->getConfigurationProduitsComplete();
    }

    public function constructId() 
    {
        $this->set('_id', "CONFIGURATION");
    }
    
    public function getCertifications()
    {
    	$certifications = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		$certifications = array_merge($certifications, $configurationProduits->getCertifications());
    	}
    	return $certifications;
    }
    
    public function getLabels($hash = null)
    {
    	$labels = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		if ($l = $configurationProduits->getLabels($hash)) {
    			$labels = array_merge($labels, $l);
    		}
    	}
    	return $labels;
    }
    
    public function getConfigurationProduits($interpro)
    {
    	$variable = '_configuration_produits_'.str_replace(Interpro::INTERPRO_KEY, '', $interpro);
    	if (is_null($this->$variable)) {
    		$this->$variable = ($this->produits->exist($interpro))? acCouchdbManager::getClient()->retrieveDocumentById($this->produits->get($interpro)) : null;
            if (!sfConfig::get('sf_debug')) {
    		    $this->$variable->loadAllData();
            }
    	}
    	return $this->$variable;
    }
    
    public function getConfigurationProduitsComplete()
    {
    	$configuration = array();
    	foreach ($this->produits->toArray() as $interpro => $configurationProduits) {
    		$configuration[$interpro] = $this->getConfigurationProduits($interpro);
    	}
    	return $configuration;
    }

    public function getFormattedProduits($hash = null, $zones, $onlyForDrmVrac = false, $format = "%g% %a% %m% %l% %co% %ce%", $cvoNeg = false, $date = null)
    {
    	$produits = array();
    	foreach ($zones as $zoneId => $zone) {
	    	foreach ($zone->getConfigurationProduits() as $configurationProduitsId => $configurationProduits) {
	    		$produits = array_merge($produits, $configurationProduits->getProduits($hash, $onlyForDrmVrac, $cvoNeg, $date));
	    	}
    	}
    	return $this->formatWithCode($produits, $format);
    }
    
    public function getFormattedLieux($hash = null, $zones, $format = "%g% %a% %m% %l%")
    {
    	$lieux = array();
    	foreach ($zones as $zoneId => $zone) {
	    	foreach ($zone->getConfigurationProduits() as $configurationProduitsId => $configurationProduits) {
	    		$lieux = array_merge($lieux, $configurationProduits->getTotalLieux($hash));
	    	}
    	}
    	return $this->format($lieux, $format);
    }
    
    protected function format($produits, $format = "%g% %a% %m% %l% %co% %ce%")
    {
    	$result = array();
    	$client = ConfigurationProduitClient::getInstance();
    	foreach ($produits as $hash => $produit) {
    		$result[$hash] = $client->format($produit->getLibelles(), array(), $format);
    	}
    	return $result;
    }
    
    protected function formatWithCode($produits, $format = "%g% %a% %m% %l% %co% %ce%")
    {
    	$result = array();
    	$client = ConfigurationProduitClient::getInstance();
    	foreach ($produits as $hash => $produit) {
    		$result[$hash] = $client->format($produit->getLibelles(), array(), $format).' '.$this->constructCode($produit);
    	}
    	return $result;
    }
    
    protected function constructCode($produit) {
            $codes = $produit->getCodes();
            $code_produit = '';
            foreach ($codes as $k => $c) {
                if(!$k) {
                    $code_produit .= ($c)? $c : '';
                }
                else{
                    $code_produit .= ($c)? $c : '';
                }
            }
            return $code_produit;
    }
    
    
    public function getConfigurationProduit($hash)
    {
    	
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		if ($configurationProduits->exist($hash)) {
    			return $configurationProduits->get($hash);
    		}
    	}
    	return null;
    }
    
    public function getConfigurationVracByInterpro($interpro)
    {
    	return $this->vrac->interpro->get($interpro);
    }
    
    public function getConfigurationDAIDS($interpro)
    {
    	return $this->daids->interpro->get($interpro);
    }
    
    public function getAllZones()
    {
    	if (is_null($this->_zones)) {
    		$this->_zones = array();
    		foreach ($this->zones as $zone) {
    			$this->_zones[$zone] = acCouchdbManager::getClient()->retrieveDocumentById($zone);
    		}
    	}
    	return $this->_zones;
    }
    
    public function getAdministratriceZones($administratrice = true)
    {
    	$zones = $this->getAllZones();
    	$result = array();
    	foreach ($zones as $id => $zone) {
    		if ($zone->administratrice) {
    			$result[$id] = $zone;
    		}
    	}
    	return ($administratrice)? $result : array_diff_key($zones, $result);
    }
    
    public function getTransparenteZones($transparente = true)
    {
    	$zones = $this->getAllZones();
    	$result = array();
    	foreach ($zones as $id => $zone) {
    		if ($zone->transparente) {
    			$result[$id] = $zone;
    		}
    	}
    	return ($transparente)? $result : array_diff_key($zones, $result);
    }

    public function save() 
    {
        parent::save();
        ConfigurationClient::getInstance()->cacheResetCurrent();
    }

    public function prepareCache() 
    {
      $this->loadAllData();
    }
}

