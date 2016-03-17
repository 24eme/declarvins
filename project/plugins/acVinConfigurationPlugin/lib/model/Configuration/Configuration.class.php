<?php

/**
 * Model for Configuration
 *
 */
class Configuration extends BaseConfiguration {

    protected $_configuration_produits_IR = null;
    protected $_configuration_produits_CIVP = null;
    protected $_configuration_produits_IVSE = null;
    protected $_configuration_produits_ANIVIN = null;
    protected $_configuration_produits_CIVL = null;
    protected $_configuration_produits_IO = null;
    protected $_zones = null;

    protected static $contraintes_vci = array(
    		'entrees/recolte',
    		'sorties/repli',
        	'sorties/distillation'
    );
    
    protected static $stocks_debut = array(
        'bloque' => 'Dont Vin bloqué / Reserve',
        'warrante' => 'Dont Vin warranté',
        'instance' => 'Dont Vin en instance'
    );
    protected static $stocks_debut_acq = array();
    protected static $stocks_entree = array(
        'achat' => 'Achats / réintégration',
        'recolte' => 'Récolte / revendication',
        'repli' => 'Mvt. interne : Replis / Changt. de dénomination',
        'declassement' => 'Mvt. interne : Déclassement / Lies',
    	'manipulation' => 'Mvt. interne : Augmentation de volume',
    	'vci' => 'Mvt. interne : Intégration issue de VCI',
        'mouvement' => 'Mvt. temporaire : Retour transfert de chai',
        'embouteillage' => 'Mvt. temporaire : Retour embouteillage',
        'travail' => 'Mvt. temporaire : Retour de travail à façon',
        'distillation' => 'Mvt. temporaire : Retour de distillation à façon',    		
        'crd' => 'Replacement en suspension CRD',
    	'excedent' => 'Excédent suite à inventaire ou contrôle douanes'
    );
    protected static $stocks_entree_acq = array(
        'acq_achat' => 'Achats',
        'acq_autres' => 'Autres'
    );
    protected static $stocks_sortie = array(
        'vrac' => 'Vrac DAA / DAE',
        'export' => 'Conditionné export',
        'factures' => 'DSA / Tickets / Factures',
        'crd' => 'CRD France',
        'consommation' => 'Conso Fam. / Analyses / Dégustation',
        'pertes' => 'Pertes et manquants constatés',
        'autres' => 'Destruction / Distillation',
        'declassement' => 'Mvt. interne : Non rev. / Déclassement',
        'repli' => 'Mvt. interne : Changement / Repli',
        'mutage' => 'Mvt. interne : Mutage',
        'vci' => 'Mvt. interne : Revendication de VCI',
        'autres_interne' => 'Mvt. interne : Autres',
        'mouvement' => 'Mvt. temporaire : Transfert de chai',
        'embouteillage' => 'Mvt. temporaire : Embouteillage',
        'travail' => 'Mvt. temporaire : Travail à façon',
        'distillation' => 'Mvt. temporaire : Distillation à façon',
        'lies' => 'Lies',
        'vrac_contrat' => 'Contrat Vrac'
    );
    protected static $stocks_sortie_acq = array(
        'acq_crd' => 'Ventes de produits',
        'acq_replacement' => 'Replacement en suspension',
        'acq_autres' => 'Autres'
    );
    protected static $stocks_fin = array(
        'bloque' => 'Dont Vin bloqué / Reserve',
        'warrante' => 'Dont Vin warranté',
        'instance' => 'Dont Vin en instance',
    );
    protected static $stocks_fin_acq = array();
    protected static $mouvement_coefficient_entree = array(
        'achat' => 1,
        'recolte' => 1,
        'repli' => 1,
        'declassement' => 1,
    	'manipulation' => 1,
    	'vci' => 1,
        'mouvement' => 1,
        'embouteillage' => 1,
        'travail' => 1,
        'distillation' => 1,    		
        'crd' => 1,
    	'excedent' => 1
    );
    protected static $mouvement_coefficient_sortie = array(
        'vrac' => -1,
        'export' => -1,
        'factures' => -1,
        'crd' => -1,
        'consommation' => -1,
        'pertes' => -1,
        'declassement' => -1,
        'repli' => -1,
        'mutage' => -1,
        'vci' => -1,
        'autres_interne' => -1,
        'mouvement' => -1,
        'embouteillage' => -1,
        'travail' => -1,
        'distillation' => -1,
        'lies' => -1,
        'autres' => -1,
        'vrac_contrat' => -1
    );
    


    public static function getContraintes($genre) {
    	if ($genre == 'VCI') {
    		return self::$contraintes_vci;
    	}
    	return array();
    }

    public static function getStocksDebut($acquittes = false) {
        return ($acquittes)? self::$stocks_debut_acq : self::$stocks_debut;
    }

    public static function getStocksEntree($acquittes = false) {
        return ($acquittes)? self::$stocks_entree_acq : self::$stocks_entree;
    }

    public static function getStocksSortie($acquittes = false) {
        return ($acquittes)? self::$stocks_sortie_acq : self::$stocks_sortie;
    }

    public static function getStocksFin($acquittes = false) {
        return ($acquittes)? self::$stocks_fin_acq : self::$stocks_fin;
    }

    public static function getAllStocksLibelles() {
        $stocks_libelles = array();

        foreach (self::$stocks_debut as $key => $value) {
            $stocks_libelles['debuts/' . $key] = 'Début ' . $value;
        }

        foreach (self::$stocks_entree as $key => $value) {
            $stocks_libelles['entrees/' . $key] = 'Entrée ' . $value;
        }

        foreach (self::$stocks_sortie as $key => $value) {
            if ($key == 'vrac_contrat') {
                $stocks_libelles['sorties/' . $key] = 'Dont Sortie ' . $value;
            } else {
                $stocks_libelles['sorties/' . $key] = 'Sortie ' . $value;
            }
        }

        foreach (self::$stocks_fin as $key => $value) {
            $stocks_libelles['fins/' . $key] = 'Fin ' . $value;
        }

        return $stocks_libelles;
    }

    public static function getAllStocksCoeffsMouvements() {
        $stocks_coeffs_mouvement = array();

        foreach (self::$mouvement_coefficient_entree as $key => $value) {
            $stocks_coeffs_mouvement['entrees/' . $key] = $value;
        }

        foreach (self::$mouvement_coefficient_sortie as $key => $value) {
            $stocks_coeffs_mouvement['sorties/' . $key] = $value;
        }

        return $stocks_coeffs_mouvement;
    }

    public function loadAllData() {
        parent::loadAllData();
        $this->getConfigurationProduitsComplete();
    }

    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }

    public function getCertifications() {
        $certifications = array();
        $configuration = $this->getConfigurationProduitsComplete();
        foreach ($configuration as $interpro => $configurationProduits) {
            $certifications = array_merge($certifications, $configurationProduits->getCertifications());
        }
        return $certifications;
    }

    public function getLabels($hash = null) {
        $labels = array();
        $configuration = $this->getConfigurationProduitsComplete();
        foreach ($configuration as $interpro => $configurationProduits) {
            if ($l = $configurationProduits->getLabels($hash)) {
                $labels = array_merge($labels, $l);
            }
        }
        return $labels;
    }

    public function getConfigurationProduits($interpro) {
        $variable = '_configuration_produits_' . str_replace(Interpro::INTERPRO_KEY, '', $interpro);
        if (is_null($this->$variable)) {
            $this->$variable = ($this->produits->exist($interpro)) ? acCouchdbManager::getClient()->retrieveDocumentById($this->produits->get($interpro)) : null;
            if (!sfConfig::get('sf_debug')) {
                //$this->$variable->loadAllData();
            }
        }
        return $this->$variable;
    }

    public function getConfigurationProduitsComplete() {
        $configuration = array();
        foreach ($this->produits->toArray() as $interpro => $configurationProduits) {
            $configuration[$interpro] = $this->getConfigurationProduits($interpro);
        }
        return $configuration;
    }

    public function getFormattedProduits($hash = null, $zones, $onlyForDrmVrac = false, $format = "%g% %a% %m% %l% %co% %ce%", $cvoNeg = false, $date = null) {
        $produits = array();
        foreach ($zones as $zoneId => $zone) {
            foreach ($zone->getConfigurationProduits() as $configurationProduitsId => $configurationProduits) {
                $produits = array_merge($produits, $configurationProduits->getProduits($hash, $onlyForDrmVrac, $cvoNeg, $date));
            }
        }
        return $this->formatWithCode($produits, $format);
    }

    public function getFormattedLieux($hash = null, $zones, $format = "%g% %a% %m% %l%") {
        $lieux = array();
        foreach ($zones as $zoneId => $zone) {
            foreach ($zone->getConfigurationProduits() as $configurationProduitsId => $configurationProduits) {
                $lieux = array_merge($lieux, $configurationProduits->getTotalLieux($hash));
            }
        }
        return $this->format($lieux, $format);
    }

    public function getFormattedCouleurs($hash = null, $zones, $onlyForDrmVrac = false, $format = "%g% %a% %m% %l% %co%", $cvoNeg = false, $date = null) {
    	$produits = array();
        foreach ($zones as $zoneId => $zone) {
            foreach ($zone->getConfigurationProduits() as $configurationProduitsId => $configurationProduits) {
                $produits = array_merge($produits, $configurationProduits->getTotalCouleurs($hash, $onlyForDrmVrac, $cvoNeg, $date));
            }
        }
        return $this->formatWithCode($produits, $format);
    }

    public function format($produits, $format = "%g% %a% %m% %l% %co% %ce%") {
        $result = array();
        $client = ConfigurationProduitClient::getInstance();
        foreach ($produits as $hash => $produit) {
            $result[$hash] = $client->format($produit->getLibelles(), array(), $format);
        }
        return $result;
    }

    public function formatWithCode($produits, $format = "%g% %a% %m% %l% %co% %ce%") {
        $result = array();
        $client = ConfigurationProduitClient::getInstance();
        foreach ($produits as $hash => $produit) {
            $result[$hash] = $client->format($produit->getLibelles(), array(), $format) . ' ' . $this->constructCode($produit);
        }
        return $result;
    }

    protected function constructCode($produit) {
        $codes = $produit->getCodes();
        $code_produit = '';
        foreach ($codes as $k => $c) {
            if (!$k) {
                $code_produit .= ($c) ? $c : '';
            } else {
                $code_produit .= ($c) ? $c : '';
            }
        }
        return $code_produit;
    }

    public function getConfigurationProduit($hash) {
        $configuration = $this->getConfigurationProduitsComplete();
        foreach ($configuration as $interpro => $configurationProduits) {
            if ($configurationProduits->exist($hash)) {
                return $configurationProduits->get($hash);
            }
        }
        return null;
    }

    public function getConfigurationVracByInterpro($interpro) {
        return $this->vrac->interpro->get($interpro);
    }

    public function getConfigurationDAIDS($interpro) {
        return $this->daids->interpro->get($interpro);
    }

    public function getAllZones() {
        if (is_null($this->_zones)) {
            $this->_zones = array();
            foreach ($this->zones as $zone) {
                $this->_zones[$zone] = acCouchdbManager::getClient()->retrieveDocumentById($zone);
            }
        }
        return $this->_zones;
    }

    public function getAdministratriceZones($administratrice = true) {
        $zones = $this->getAllZones();
        $result = array();
        foreach ($zones as $id => $zone) {
            if ($zone->administratrice) {
                $result[$id] = $zone;
            }
        }
        return ($administratrice) ? $result : array_diff_key($zones, $result);
    }

    public function getTransparenteZones($transparente = true) {
        $zones = $this->getAllZones();
        $result = array();
        foreach ($zones as $id => $zone) {
            if ($zone->transparente) {
                $result[$id] = $zone;
            }
        }
        return ($transparente) ? $result : array_diff_key($zones, $result);
    }

    public function save() {
        parent::save();
        ConfigurationClient::getInstance()->cacheResetCurrent();
    }

    public function prepareCache() {
        $this->loadAllData();
    }
    
    public function isApplicationOuverte($interpro, $application, $etablissement = null) {
    		try {
  				$ouverture = $this->ouverture->get($interpro)->get($application);
  			} catch (Exception $e) { 
  				$ouverture = 0;
  			}
  			return $ouverture;
    }

}
