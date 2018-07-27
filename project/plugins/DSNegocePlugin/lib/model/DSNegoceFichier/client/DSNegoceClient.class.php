<?php

class DSNegoceClient extends acCouchdbClient {
	
	const TYPE_MODEL = 'DSNEGOCE';
	
    public static function getInstance()
    {
      return acCouchdbManager::getClient("DSNegoce");
    }

    public function findByArgs($identifiant, $periode)
    {
    	$id = self::TYPE_MODEL.'-' . $identifiant . '-' . $annee;
    	return $this->find($id);
    }
    
    protected function getPeriodeByDate($date = null)
    {
    	return substr($date, 0, -3);
    }

    public function createDoc($identifiant, $date, $papier = false)
    {
        $fichier = new DSNegoce();
        $fichier->initDoc($identifiant);
        $fichier->date_import = $date;
        $fichier->date_depot = $date;
        $fichier->visibilite = 1;        
        $fichier->periode = $this->getPeriodeByDate($date);
        $cm = new CampagneManager('08-01');
        $fichier->campagne = $cm->getCampagneByDate($date);
        $fichier->libelle = "Déclaration de Stock ".$fichier->periode;
        if($papier) {
            $fichier->add('papier', 1);
        }
        return $fichier;
    }
    
    public function findByIdentifiantPeriode($identifiant,  $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$date = explode('-', sfConfig::get('app_dsnegoce_date'));
    	return $this->find(sprintf(self::TYPE_MODEL."-%s-%s-%s", $identifiant, $date[0], $date[1]));
    }
    
    public function findByIdentifiant($identifiant,  $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$view = $this->startkey(sprintf(self::TYPE_MODEL."-%s-%s-%s", $identifiant, "0000", "00"))
    	->endkey(sprintf(self::TYPE_MODEL."-%s-%s-%s", $identifiant, "9999", "99"));
    	return $view->execute($hydrate)->getDatas();
    }

    public function findAll($limit = null, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$view = $this->startkey(sprintf(self::TYPE_MODEL."-%s-%s-%s", "00000000", "0000", "00"))
    				 ->endkey(sprintf(self::TYPE_MODEL."-%s-%s-%s", "99999999", "9999", "99"));
    	if ($limit) {
    		$view->limit($limit);
    	}
    	return $view->execute($hydrate)->getDatas();
    }
}
