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
    
    public function needAvenant()
    {
    	$need = false;
    	foreach ($this->etablissements as $etab) {
    		if ($etab->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR && $etab->zones->exist(ConfigurationZoneClient::ZONE_PROVENCE)) {
    			$need = true;
    		}
    	}
    	return $need;
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
    

    
    public function getConfigurationZonesForSend()
    {
    	$result = array();
    	foreach ($this->etablissements as $etablissement) {
    		$resultEtablissements = array();
    		foreach ($etablissement->zones as $zoneId => $zone) {
    			if (!in_array($zoneId, array_keys($result))) {
    				$resultEtablissements[$zoneId] = $zoneId;
    			}
    		}
    		if (in_array(ConfigurationZoneClient::ZONE_IVSE, $resultEtablissements)) {
    			if (in_array(ConfigurationZoneClient::ZONE_RHONE, $resultEtablissements)) {
    				unset($resultEtablissements[ConfigurationZoneClient::ZONE_IVSE]);
    			}
    			if (in_array(ConfigurationZoneClient::ZONE_PROVENCE, $resultEtablissements)) {
    				unset($resultEtablissements[ConfigurationZoneClient::ZONE_IVSE]);
    			}
    		}
    		foreach ($resultEtablissements as $resultEtablissement) {
    			$result[$resultEtablissement] = ConfigurationZoneClient::getInstance()->find($resultEtablissement);
    		}
    	}
    	return $result;
    }

    public function generateByGrc($datas) {
        $this->nom = $datas[EtablissementCsv::COL_CHAMPS_COMPTE_NOM];
        $this->prenom = $datas[EtablissementCsv::COL_CHAMPS_COMPTE_PRENOM];
        $this->telephone = $datas[EtablissementCsv::COL_CHAMPS_COMPTE_TELEPHONE];
        $this->fax = $datas[EtablissementCsv::COL_CHAMPS_COMPTE_FAX];
        $this->email = $datas[EtablissementCsv::COL_CHAMPS_COMPTE_EMAIL];
        $etab = $this->etablissements->add();
        $etab->nom = trim($datas[EtablissementCsv::COL_NOM]);
        $etab->raison_sociale = trim($datas[EtablissementCsv::COL_RAISON_SOCIALE]);
        $etab->siret = trim($datas[EtablissementCsv::COL_SIRET]);
        $etab->cni = trim($datas[EtablissementCsv::COL_CNI]);
        $etab->cvi = trim($datas[EtablissementCsv::COL_CVI]);
        $etab->no_accises = trim($datas[EtablissementCsv::COL_NO_ASSICES]);
        $etab->no_tva_intracommunautaire = trim($datas[EtablissementCsv::COL_NO_TVA_INTRACOMMUNAUTAIRE]);
        $etab->no_carte_professionnelle = trim($datas[EtablissementCsv::COL_NO_CARTE_PROFESSIONNELLE]);
        $etab->email = trim($datas[EtablissementCsv::COL_EMAIL]);
        $etab->telephone = trim($datas[EtablissementCsv::COL_TELEPHONE]);
        $etab->fax = trim($datas[EtablissementCsv::COL_FAX]);
        $etab->adresse = trim($datas[EtablissementCsv::COL_ADRESSE]);
        $etab->code_postal = trim($datas[EtablissementCsv::COL_CODE_POSTAL]);
        $etab->commune = trim($datas[EtablissementCsv::COL_COMMUNE]);
        $etab->pays = trim($datas[EtablissementCsv::COL_PAYS]);
        $etab->comptabilite_adresse = trim($datas[EtablissementCsv::COL_COMPTA_ADRESSE]);
        $etab->comptabilite_code_postal = trim($datas[EtablissementCsv::COL_COMPTA_CODE_POSTAL]);
        $etab->comptabilite_commune = trim($datas[EtablissementCsv::COL_COMPTA_COMMUNE]);
        $etab->comptabilite_pays = trim($datas[EtablissementCsv::COL_COMPTA_PAYS]);
        $etab->service_douane = trim($datas[EtablissementCsv::COL_SERVICE_DOUANE]);
        $etab->famille = EtablissementClient::getInstance()->matchFamille(KeyInflector::slugify(trim($datas[EtablissementCsv::COL_FAMILLE])));
        $etab->sous_famille = EtablissementClient::getInstance()->matchSousFamille(trim($datas[EtablissementCsv::COL_SOUS_FAMILLE]));
        $etab->add('zones');
        $zones = explode('|', $datas[EtablissementCsv::COL_ZONES]);
		$zones = array_merge($zones, array_keys(ConfigurationClient::getCurrent()->getTransparenteZones()));
        $result = array();
    	foreach ($zones as $zone) {
    		$result[] = ConfigurationZoneClient::getInstance()->matchZone($zone);
    	}
        $confZones = array();
        foreach (ConfigurationZoneClient::getInstance()->getAllZones() as $z) {
        	$confZones[$z] = ConfigurationZoneClient::getInstance()->find($z);
        }
        foreach ($result as $confZoneId) {
        	$confZone = $confZones[$confZoneId];
        	$z = $etab->zones->add($confZoneId);
        	$z->libelle = $confZone->libelle;
        	$z->transparente = $confZone->transparente;
        	$z->administratrice = $confZone->administratrice;
        }
    }
}
