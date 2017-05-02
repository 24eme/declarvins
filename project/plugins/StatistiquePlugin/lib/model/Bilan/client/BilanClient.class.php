<?php

class BilanClient extends acCouchdbClient {

    public static function getInstance() {
        return acCouchdbManager::getClient("Bilan");
    }

    public function buildId($identifiant, $type = 'DRM') {
        return 'BILAN-' . $type . '-' . $identifiant;
    }
    
    public function findOrCreateByIdentifiant($identifiant, $type = 'DRM', $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        if ($obj = $this->findByIdentifiantAndType($identifiant, $type, $hydrate)) {
            return $obj;
        }

        $obj = new Bilan();
        $obj->identifiant = $identifiant;
        $obj->type_bilan = $type;
        $obj->constructId();
        return $obj;
    }
    
    public function findByIdentifiantAndType($identifiant, $type, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        return $this->find($this->buildId($identifiant, $type), $hydrate);
    }
    
    public function getStatutSimple($statut) {
    	if (in_array($statut, array(DRMClient::DRM_STATUS_BILAN_IGP_MANQUANT, DRMClient::DRM_STATUS_BILAN_CONTRAT_MANQUANT, DRMClient::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT))) {
    		return DRMClient::DRM_STATUS_BILAN_VALIDE;
    	}
    
    	return $statut;
    }
    
    public function getStatutLibelleSimple($statut) {
    	return DRMClient::getLibellesForStatusBilan($this->getStatutSimple($statut));
    }

}
