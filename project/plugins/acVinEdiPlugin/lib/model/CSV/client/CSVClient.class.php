<?php

class CSVClient extends acCouchdbClient {

    const TYPE_DRM = "DRM";
    const TYPE_VRAC = "VRAC";

    public static function getInstance() {
        return acCouchdbManager::getClient("CSV");
    }

    public function createOrFindDocFromDRM($path, DRM $drm) {
        $csvId = $this->buildId(self::TYPE_DRM, $drm->identifiant, $drm->periode);
        $csvDrm = $this->find($csvId);
        if ($csvDrm) { 
            $csvDrm->storeAttachment($path, 'text/csv', $csvDrm->getFileName());
            return $csvDrm;
        }
        $csvDrm = new CSV();
        $csvDrm->_id = $csvId;
        $csvDrm->identifiant = $drm->identifiant;
        $csvDrm->periode = $drm->periode;
        $csvDrm->storeAttachment($path, 'text/csv', $csvDrm->getFileName());
        $csvDrm->save();
        return $csvDrm;
    }

    public function createOrFindDocFromVrac($path, Vrac $vrac) {
        $csvId = $this->buildId(self::TYPE_VRAC, $vrac->numero_contrat);
        $csvVrac = $this->find($csvId);
        if ($csvVrac) { 
            $csvVrac->storeAttachment($path, 'text/csv', $csvVrac->getFileName());
            return $csvVrac;
        }
        $csvVrac = new CSV();
        $csvVrac->_id = $csvId;
        $csvVrac->identifiant = $vrac->numero_contrat;
        $csvVrac->periode = null;
        $csvVrac->storeAttachment($path, 'text/csv', $csvVrac->getFileName());
        $csvVrac->save();
        return $csvVrac;
    }

    public function buildId($type_doc, $identifiant, $periode = null) {
        return ($periode)? "CSV-" . $type_doc . "-" . $identifiant . "-" . $periode : "CSV-" . $type_doc . "-" . $identifiant;
    }

}
