<?php

class DRMClient extends acCouchdbClient {
   
    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("DRM");
    }
    
    private function getCampagne($annee, $mois) {
      return sprintf("%04d-%02d", $annee, $mois);
    }
    private function getId($identifiant, $annee, $mois) {
      return 'DRM-'.$identifiant.'-'.$this->getCampagne($annee, $mois);
    }
    /**
     *
     * @param string $login
     * @param integer $hydrate
     * @return DRM
     */
    public function retrieveByIdentifiantAndCampagne($identifiant, $campagne, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
    	return parent::retrieveDocumentById('DRM-'.$identifiant.'-'.$campagne, $hydrate);
    }
    
    public function retrieveOrCreateByIdentifiantAndCampagne($identifiant, $annee, $mois, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
      if ($obj = $this->retrieveByIdentifiantAndCampagne($identifiant, $this->getCampagne($annee, $mois), $hydrate))
	return $obj;
      $obj = new DRM();
      $obj->_id = $this->getId($identifiant, $annee, $mois);
      $obj->identifiant = $identifiant;
      $obj->campagne = $this->getCampagne($annee, $mois);
      return $obj;
    }

}
