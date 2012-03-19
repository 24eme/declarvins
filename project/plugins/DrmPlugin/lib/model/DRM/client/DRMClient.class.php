<?php

class DRMClient extends acCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
      return acCouchdbManager::getClient("DRM");
    }

    public function getId($identifiant, $campagne, $rectificative = null) {
      
      return 'DRM-'.$identifiant.'-'.$this->getCampagneAndRectificative($campagne, $rectificative);
    }

    public function getCampagne($annee, $mois) {

      return sprintf("%04d-%02d", $annee, $mois);
    }

    public function getAnnee($campagne) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$1', $campagne);
    }

    public function getMois($campagne) {

      return preg_replace('/([0-9]{4})-([0-9]{2})/', '$2', $campagne);
    }

    public function getCampagneAndRectificative($campagne, $rectificative = null) {
      if($rectificative  && $rectificative > 0) {

        return $campagne.'-R'.sprintf("%02d", $rectificative);
      } else {

        return $campagne;
      }
    }

    public function findLastByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      $drms = $this->viewByIdentifiantCampagne($identifiant, $campagne);

      foreach($drms as $id => $drm) {

        return $this->find($id, $hydrate);
      }

      return null;
    }

    public function findByIdentifiantCampagneAndRectificative($identifiant, $campagne, $rectificative = null, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

      return $this->find($this->getId($identifiant, $campagne, $rectificative, $hydrate));
    }
    
    public function retrieveOrCreateByIdentifiantAndCampagne($identifiant, $annee, $mois, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      if ($obj = $this->findLastByIdentifiantAndCampagne($identifiant, $this->getCampagne($annee, $mois), $hydrate)) {
        return $obj;
      }

      $obj = new DRM();
      $obj->identifiant = $identifiant;
      $obj->campagne = $this->getCampagne($annee, $mois);
      
      return $obj;
    }

    protected function viewByIdentifiantCampagne($identifiant, $campagne) {
      $annee = $this->getAnnee($campagne);
      $mois = $this->getMois($campagne);

      $rows = acCouchdbManager::getClient()
            ->startkey(array($identifiant, $annee, $mois))
              ->endkey(array($identifiant, $annee, $mois, array()))
              ->reduce(false)
              ->getView("drm", "all")
              ->rows;
      
      $drms = array();

      foreach($rows as $row) {
        $drms[$row->id] = $row->key;
      }
      
      krsort($drms);
      
      return $drms;
    }

}
