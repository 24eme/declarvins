<?php

class ConfigurationClient extends acCouchdbClient
{

	private static $current = null;
	protected $countries = null;
	protected $devises = null;
	protected $configurations = array();

	protected $saltToken = null;

    const CAMPAGNE_DATE_DEBUT = '%s-08-01';
    const CAMPAGNE_DATE_FIN = '%s-07-31';

	public static function getInstance()
	{
	  	return acCouchdbManager::getClient("CONFIGURATION");
	}

    public static function getConfiguration($date = null) {

        return self::getInstance()->findConfiguration($date);
    }

    public function findConfiguration($date = null) {
        if(is_null($date)) {
            $date = date('Y-m-d');
        }

        $current = CurrentClient::getCurrent();
        $id = $current->getConfigurationId($date);

        if(array_key_exists($id, $this->configurations)) {

            return $this->configurations[$id];
        }

        $this->configurations[$id] = $this->cacheFindConfigurationForCache($id);

        return $this->configurations[$id];
    }

    public function cacheFindConfigurationForCache($id) {

        return CacheFunction::cache('model', "ConfigurationClientCache::findConfigurationForCache", array($id));
    }

    public function findConfigurationForCache($id) {
        $configuration = $this->find($id);
        $configuration->prepareCache();

        return $configuration;
    }

    public function cacheResetConfiguration() {
        CacheFunction::remove('model');
    }

	public static function getCurrent($date = null)
	{
		return self::getConfiguration($date);
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

    public function getCurrentCampagne() {

    	return $this->buildCampagne(date('Y-m-d'));
    }

	public function getCountryList() {
		if(is_null($this->countries)) {
			$destinationChoicesWidget = new sfWidgetFormI18nChoiceCountry(array('culture' => 'fr'));
			$this->countries = $destinationChoicesWidget->getChoices();
			$this->countries['MF'] = 'Saint-Martin (partie française)';
			$this->countries['SX'] = 'Saint-Martin (partie néerlandaise)';
			$this->countries['QR'] = 'Avitaillement et soutage dans le cadre des echanges intra-UE';
			$this->countries['QS'] = 'Avitaillement et soutage dans le cadre des echanges avec les pays tiers';
			$this->countries['QU'] = 'Pays et territoires non déterminés';
			$this->countries['QW'] = 'Pays et territoires non déterminés';
			$this->countries['CS'] = 'Serbie et Monténégro';
		}
		return $this->countries;
	}

	public function getDeviseList() {
		if(is_null($this->devises)) {
			$deviseChoicesWidget = new sfWidgetFormI18nChoiceCurrency(array('culture' => 'fr'));
			$this->devises = $deviseChoicesWidget->getChoices();
		}
		return $this->devises;
	}

	public static function generateSaltToken() {

        return uniqid().rand();
    }

	public function getSaltToken() {
		if(!$this->saltToken) {
			$this->saltToken = CacheFunction::cache('model', "ConfigurationClient::generateSaltToken");
		}

		return $this->saltToken;
    }

    public function anonymisation($value) {

        return hash("ripemd128", $value.$this->getSaltToken());
    }
}


class ConfigurationClientCache {
	public static function findConfigurationForCache($id) {
        $configuration = ConfigurationClient::getInstance()->find($id);
        $configuration->prepareCache();

        return $configuration;
    }
}
