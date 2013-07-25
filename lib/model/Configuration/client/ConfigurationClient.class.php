<?php

class ConfigurationClient extends acCouchdbClient {
	
	private static $current = array();

    const CAMPAGNE_DATE_DEBUT = '%s-08-01';
    const CAMPAGNE_DATE_FIN = '%s-07-31';
    
    

	/**
	*
	* @return CurrentClient 
	*/
	public static function getInstance() {

	  	return acCouchdbManager::getClient("CONFIGURATION");
	}

    public function findCurrent($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        return $configuration = $this->find('CONFIGURATION', $hydrate);
    }

	/**
	*
	* @return Current 
	*/
	public static function getCurrent() {
		if (self::$current == null) {
            self::$current = self::getInstance()->cacheGetCurrent();
		}

		return self::$current;
	}
  

	public function findCurrentForCache() {
	  	$configuration = $this->findCurrent();
        if(!sfConfig::get('sf_debug')) {
            $configuration->prepareCache();
        }

		return $configuration;
	}

    public function cacheGetCurrent() {
        
        return CacheFunction::cache('model_configuration', array(ConfigurationClient::getInstance(), 'findCurrentForCache'), array());
    }

    public function cacheResetCurrent() {
        CacheFunction::remove('model_configuration');
    }
  
    public function findProduitsForAdmin($interpro) {
        
        return $this->startkey(array($interpro))
              ->endkey(array($interpro, array()))->getView('configuration', 'produits_admin');
    }
  
    public function findTreeProduitsLibelleForAdmin($interpro, $withCsvo = true) {
        $produits = $this->findProduitsForAdmin($interpro)->rows;
        $tree = array();
        foreach ($produits as $produit) {
        	$p = $produit->key;
        	if (!$withCsvo) {
        		$values = $produit->value;
        		if((!isset($values->cvo) || !isset($values->cvo->taux) || $values->cvo->taux == 0 || $values->cvo->taux == null || $values->cvo->taux == '')) {
        			continue;
        		}
        	}
        	$tree = array_merge($tree, $this->getProduitsByKey($p));
        }
        ksort($tree);
        return $tree;
    }
    
    public function getProduitsByKey($key)
    {
    	$explodedHash = explode('/', $key[8]);
    	$result = array();
        $codeConcat = $explodedHash[1];
        $libelleConcat = '';
    	for ($i = 2; $i<15; $i++) {
    		$codeConcat .= '/'.$explodedHash[$i];
    		if ($explodedHash[$i] == Configuration::DEFAULT_KEY) {
    			continue;
    		}
    		if ($i%2 != 0) {
    			continue;
    		}
    		$libelleConcat .= $key[$i/2].' ';
    		$result[$codeConcat] = $libelleConcat;
    	}
    	return $result;
    }
  
    public function findHashProduits($interpro, $withCsvo = true) {
        $produits = $this->findProduitsForAdmin($interpro)->rows;
        $result = array();
        foreach ($produits as $produit) {
        	$p = $produit->key;
        	if (!$withCsvo) {
        		$values = $produit->value;
        		if((!isset($values->cvo) || !isset($values->cvo->taux) || $values->cvo->taux == 0 || $values->cvo->taux == null || $values->cvo->taux == '')) {
        			continue;
        		}
        	}
        	$result[] = $p[8];
        }
        return $result;
    }
  
    public function findHashProduitsNoCvo($interpro) {
        $produits = $this->findProduitsForAdmin($interpro)->rows;
        $result = array();
        foreach ($produits as $produit) {
        	$p = $produit->key;
        	$values = $produit->value;
        	if((!isset($values->cvo) || !isset($values->cvo->taux) || $values->cvo->taux == 0 || $values->cvo->taux == null || $values->cvo->taux == '')) {
        		$result[] = $p[8];
        	}
        }
        return $result;
    }

    public function findDroitsByHashAndType($hash, $type) {
        
        return $this->startkey(array($hash, $type))
              ->endkey(array($hash, $type, array()))->getView('configuration', 'droits');
    }
    
    public function getDroitsByHashAndTypeAndPeriode($hash, $type, $periode = null)
    {
    	if (!$periode) {
    		$periode = date('Y-m').'-01';
    	}
    	$droits = $this->findDroitsByHashAndType($hash, $type)->rows;
        $tmp = null;
        foreach ($droits as $d) {
        	$items = $d->value;
        	foreach ($items as $item) {
	        	foreach ($item as $droit) {
	        		if (!$tmp && date('Ymd', strtotime($droit->date)) <= date('Ymd', strtotime($periode))) {
		        		$tmp = $droit;
		        	}
		        	if ($tmp && date('Ymd', strtotime($tmp->date)) <= date('Ymd', strtotime($droit->date)) && date('Ymd', strtotime($droit->date)) <= date('Ymd', strtotime($periode))) {
		        		$tmp = $droit;
		        	}
	        	}
        	}
        } 
    	return $tmp;
    }

    public function findProduitsByCertificationAndInterpro($interpro, $certif) {
        
        return $this->startkey(array($interpro, $certif))
              ->endkey(array($interpro, $certif, array()))->getView('configuration', 'produits_admin');
    }

    public function buildCampagne($date) {

        return sprintf('%s-%s', date('Y', strtotime($this->buildDateDebutCampagne($date))), date('Y', strtotime($this->buildDateFinCampagne($date))));
    }

    public function getDateDebutCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annees[1]); 
    }

    public function getDateFinCampagne($campagne) {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {

            throw new sfException('campagne bad format');
        }

        return sprintf(self::CAMPAGNE_DATE_FIN, $annees[2]); 
    }

    public function buildDateDebutCampagne($date) {
        $annee = date('Y', strtotime($date));
        if(date('m', strtotime($date)) < 8) {
            $annee -= 1;
        }

        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annee); 
    }

    public function buildDateFinCampagne($date) {

      return sprintf(self::CAMPAGNE_DATE_FIN, date('Y', strtotime($this->buildDateDebutCampagne($date)))+1);
    }
  
}
