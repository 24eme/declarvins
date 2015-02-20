<?php
class Contrat extends BaseContrat {
    
    protected $_compte = null;
    
    /**
     * @return _Compte
     */
    public function getCompteObject() {
        if (is_null($this->_compte)) {
            $this->_compte = acCouchdbManager::getClient('_Compte')->retrieveDocumentById($this->compte);
        }
        return $this->_compte;
    }
    
    public function getDepartementsEtablissements()
    {
    	$departements = array();
    	foreach ($this->etablissements as $etablissement) {
    		$departements[] = substr($etablissement->code_postal, 0, 2);
    	}
    	return $departements;
    }
    
    public function addZones($etablissementKey, $zonesIds)
    {
    	foreach ($zonesIds as $zoneId) {
    		$this->addZone($etablissementKey, ConfigurationZoneClient::getInstance()->find($zoneId));
    	}
    }
    
    public function addZone($etablissementKey, $zone)
    {
    	$zones = $this->etablissements->get($etablissementKey)->getOrAdd('zones');
    	$z = $zones->getOrAdd($zone->_id);
    	$z->libelle = $zone->libelle;
    	$z->transparente = $zone->transparente;
    	$z->administratrice = $zone->administratrice;
    }
    
    public function getConfigurationZones()
    {
    	$result = array();
    	foreach ($this->etablissements as $etablissement) {
    		foreach ($etablissement->zones as $zoneId => $zone) {
    			if (!in_array($zoneId, array_keys($result))) {
    				$result[$zoneId] = ConfigurationZoneClient::getInstance()->find($zoneId);
    			}
    		}
    	}
    	return $result;
    }
}