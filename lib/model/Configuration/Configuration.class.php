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
    
    protected static $stocks_debut = array(
    	'bloque' => 'Dont Vin bloqué',
    	'warrante' => 'Dont Vin warranté',
    	'instance' => 'Dont Vin en instance',
    	'commercialisable' => 'Dont commercialisable'
    );
    
    protected static $stocks_entree = array(
    	'achat' => 'Achats',
    	'recolte' => 'Récolte',
    	'repli' => 'Replis / Changement de dénomination',
    	'declassement' => 'Déclassement',
    	'mouvement' => 'Transfert de chai / Embouteillage',
    	'crd' => 'Réintégration CRD'
    );
    
    protected static $stocks_sortie = array(
    	'vrac' => 'Vrac DAA/DAE',
    	'export' => 'Conditionné Export',
    	'factures' => 'DSA / Tickets / Factures',
    	'crd' => 'CRD France',
    	'consommation' => 'Conso Fam. / Analyses / Dégustation',
    	'pertes' => 'Pertes',
    	'declassement' => 'Non rev. / Déclassement',
    	'repli' => 'Changement / Repli',
    	'mouvement' => 'Transfert de chai / Embouteillage',
    	'distillation' => 'Distillation',
    	'lies' => 'Lies'
    );
    
    protected static $stocks_fin = array(
    	'bloque' => 'Dont Vin bloqué',
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
    		$labels = array_merge($labels, $configurationProduits->getLabels($hash));
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

    public function getFormattedProduits($hash = null, $departements, $onlyForDrmVrac = false, $format = "%g% %a% %m% %l% %co% %ce%", $cvoNeg = false, $date = null)
    {
    	$produits = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		$produits = array_merge($produits, $configurationProduits->getProduits($hash, $departements, $onlyForDrmVrac, $cvoNeg, $date));
    	}
    	return $this->formatWithCode($produits, $format);
    }
    
    public function getFormattedLieux($hash = null, $departements, $format = "%g% %a% %m% %l%")
    {
    	$lieux = array();
    	$configuration = $this->getConfigurationProduitsComplete();
    	foreach ($configuration as $interpro => $configurationProduits) {
    		$lieux = array_merge($lieux, $configurationProduits->getTotalLieux($hash, $departements));
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

