<?php

class ConfigurationZoneClient extends acCouchdbClient 
{
	
	const ZONE_RHONE = 'CONFIGURATION-ZONE-RHONE';
	const ZONE_PROVENCE = 'CONFIGURATION-ZONE-PROVENCE';
	const ZONE_IVSE = 'CONFIGURATION-ZONE-IGP-IVSE';
	const ZONE_LANGUEDOC = 'CONFIGURATION-ZONE-IGP-LANGUEDOC';
	const ZONE_ANIVIN = 'CONFIGURATION-ZONE-ANIVIN';
	
	protected static $_zones = array(self::ZONE_RHONE, self::ZONE_PROVENCE, self::ZONE_IVSE, self::ZONE_LANGUEDOC, self::ZONE_ANIVIN);
	
	public static function getInstance() 
	{
	  	return acCouchdbManager::getClient("ConfigurationZone");
	}
    
    public function getAllZones() {
    	return self::$_zones;
    }

	public function getZonesInitialConfiguration() 
	{
    	
    	$rhone = new ConfigurationZone();
    	$rhone->identifiant = 'RHONE';
    	$rhone->libelle = 'Zone Rhône';
    	$rhone->administratrice = 1;
    	$rhone->transparente = 0;
    	$rhone->liaisons = array('INTERPRO-IR');
    	$rhone->produits = array('CONFIGURATION-PRODUITS-IR');
    	$rhone->inscriptions = array('INTERPRO-IR');
    	$rhone->accessibilites = array('INTERPRO-IR');
    	$rhone->constructId();
    	
    	$provence = new ConfigurationZone();
    	$provence->identifiant = 'PROVENCE';
    	$provence->libelle = 'Zone Provence';
    	$provence->administratrice = 1;
    	$provence->transparente = 0;
    	$provence->liaisons = array('INTERPRO-CIVP');
    	$provence->produits = array('CONFIGURATION-PRODUITS-CIVP');
    	$provence->inscriptions = array('INTERPRO-CIVP');
    	$provence->accessibilites = array('INTERPRO-CIVP');
    	$provence->constructId();
    	
    	$ivse = new ConfigurationZone();
    	$ivse->identifiant = 'IGP-IVSE';
    	$ivse->libelle = 'IGP Intervins SE';
    	$ivse->administratrice = 0;
    	$ivse->transparente = 0;
    	$ivse->liaisons = array('INTERPRO-IVSE');
    	$ivse->produits = array('CONFIGURATION-PRODUITS-IVSE');
    	$ivse->inscriptions = array('INTERPRO-IR', 'INTERPRO-CIVP');
    	$ivse->accessibilites = array('INTERPRO-IVSE');
    	$ivse->constructId();
    	
    	$languedoc = new ConfigurationZone();
    	$languedoc->identifiant = 'IGP-LANGUEDOC';
    	$languedoc->libelle = 'IGP Languedoc';
    	$languedoc->administratrice = 0;
    	$languedoc->transparente = 0;
    	$languedoc->liaisons = array('INTERPRO-IO', 'INTERPRO-CIVL');
    	$languedoc->produits = array('CONFIGURATION-PRODUITS-IO', 'CONFIGURATION-PRODUITS-CIVL');
    	$languedoc->inscriptions = array('INTERPRO-IR');
    	$languedoc->accessibilites = array('INTERPRO-IR', 'INTERPRO-IO', 'INTERPRO-CIVL');
    	$languedoc->constructId();
    	
    	$autres = new ConfigurationZone();
    	$autres->identifiant = 'ANIVIN';
    	$autres->libelle = 'Autres vins';
    	$autres->administratrice = 0;
    	$autres->transparente = 1;
    	$autres->liaisons = array('INTERPRO-ANIVIN');
    	$autres->produits = array('CONFIGURATION-PRODUITS-ANIVIN');
    	$autres->inscriptions = array('INTERPRO-IR', 'INTERPRO-CIVP');
    	$autres->accessibilites = array('INTERPRO-IR', 'INTERPRO-CIVP', 'INTERPRO-IO', 'INTERPRO-CIVL', 'INTERPRO-ANIVIN');
    	$autres->constructId();
	    
	    $zones = array('RHONE' => $rhone, 'PROVENCE' => $provence, 'IGP-IVSE' => $ivse, 'IGP-LANGUEDOC' => $languedoc, 'ANIVIN' => $autres);
	    return $zones;
    }
    
	public function matchZone($zone) {
      if (preg_match('/rhon/i', $zone) || preg_match('/'.self::ZONE_RHONE.'/i', $zone)) {
        return self::ZONE_RHONE;
      }
      if (preg_match('/prov/i', $zone) || preg_match('/'.self::ZONE_PROVENCE.'/i', $zone)) {
        return self::ZONE_PROVENCE;
      }
      if (preg_match('/ldoc/i', $zone) || preg_match('/'.self::ZONE_LANGUEDOC.'/i', $zone)) {
        return self::ZONE_LANGUEDOC;
      }
      if (preg_match('/ivse/i', $zone) || preg_match('/'.self::ZONE_IVSE.'/i', $zone)) {
        return self::ZONE_IVSE;
      }
      if (preg_match('/vsig/i', $zone) || preg_match('/'.self::ZONE_ANIVIN.'/i', $zone)) {
        return self::ZONE_ANIVIN;
      }

      throw new sfException("La zone $zone n'est pas reconnue");
    }
    
	public function getGrcCode($zone) {
      if ($zone == self::ZONE_RHONE) {
        return 'rhon';
      }
      if ($zone == self::ZONE_PROVENCE) {
        return 'prov';
      }
      if ($zone == self::ZONE_LANGUEDOC) {
        return 'ldoc';
      }
      if ($zone == self::ZONE_IVSE) {
        return 'ivse';
      }
      if ($zone == self::ZONE_ANIVIN) {
        return 'vsig';
      }
	  return '';
    }
    
	public function getGrcLibelle($zone) {
      if ($zone == self::ZONE_RHONE) {
        return 'Rhone';
      }
      if ($zone == self::ZONE_PROVENCE) {
        return 'Provence';
      }
      if ($zone == self::ZONE_LANGUEDOC) {
        return 'Languedoc';
      }
      if ($zone == self::ZONE_IVSE) {
        return 'IVSE';
      }
      if ($zone == self::ZONE_ANIVIN) {
        return 'Anivins';
      }
	  return '';
    }
	
}
