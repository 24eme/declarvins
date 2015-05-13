<?php

class ConfigurationClient extends acCouchdbClient 
{
	
	private static $current = null;

    const CAMPAGNE_DATE_DEBUT = '%s-08-01';
    const CAMPAGNE_DATE_FIN = '%s-07-31';
    
	public static function getInstance() 
	{
	  	return acCouchdbManager::getClient("CONFIGURATION");
	}

    public function findCurrent($hydrate = acCouchdbClient::HYDRATE_DOCUMENT) 
    {
        return $this->find('CONFIGURATION', $hydrate);
    }
    
	public static function getCurrent() 
	{
		if (self::$current == null) {
            self::$current = self::getInstance()->cacheGetCurrent();
		}
		return self::$current;
	}
  

	public function findCurrentForCache() 
	{
	  	$configuration = $this->findCurrent();
        if(!sfConfig::get('sf_debug')) {
            $configuration->prepareCache();
        }

		return $configuration;
	}

    public function cacheGetCurrent() 
    {
        return CacheFunction::cache('model_configuration', array(ConfigurationClient::getInstance(), 'findCurrentForCache'), array());
    }

    public function cacheResetCurrent() 
    {
        CacheFunction::remove('model_configuration');
    }
    
    public function getProduitsByKey($key)
    {
    	$explodedHash = explode('/', $key[8]);
    	$result = array();
        $codeConcat = $explodedHash[1];
        $libelleConcat = '';
    	for ($i = 2; $i<15; $i++) {
    		$codeConcat .= '/'.$explodedHash[$i];
    		if ($explodedHash[$i] == ConfigurationProduit::DEFAULT_KEY) {
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

    public function buildCampagne($date) 
    {
        return sprintf('%s-%s', date('Y', strtotime($this->buildDateDebutCampagne($date))), date('Y', strtotime($this->buildDateFinCampagne($date))));
    }

    public function getDateDebutCampagne($campagne) 
    {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {
            throw new sfException('campagne bad format');
        }
        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annees[1]); 
    }

    public function getDateFinCampagne($campagne) 
    {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {
            throw new sfException('campagne bad format');
        }
        return sprintf(self::CAMPAGNE_DATE_FIN, $annees[2]); 
    }
    
    public function getPeriodesForCampagne($campagne) 
    {
        if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {
            throw new sfException('campagne bad format');
        }
        $periodes = array();
        for ($mois = 8; $mois <= 12; $mois++) {
            $periodes[] = sprintf("%s-%02d", $annees[1],$mois);
        }
        for ($mois = 1; $mois <= 7; $mois++) {
           $periodes[] = sprintf("%s-%02d", $annees[2],$mois); 
        }
        return $periodes; 
    }

    public function buildDateDebutCampagne($date) 
    {
        $annee = date('Y', strtotime($date));
        if(date('m', strtotime($date)) < 8) {
            $annee -= 1;
        }
        return sprintf(self::CAMPAGNE_DATE_DEBUT, $annee); 
    }

    public function buildDateFinCampagne($date) 
    {
      return sprintf(self::CAMPAGNE_DATE_FIN, date('Y', strtotime($this->buildDateDebutCampagne($date)))+1);
    }
  
}
