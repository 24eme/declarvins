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

}
