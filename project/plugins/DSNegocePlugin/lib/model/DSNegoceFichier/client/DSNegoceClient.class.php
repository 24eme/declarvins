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
    
    public function findByIdentifiant($identifiant,  $hydrate = acCouchdbClient::HYDRATE_DOCUMENT)
    {
    	$view = $this->startkey(sprintf(self::TYPE_MODEL."-%s-%s-%s-%s", $identifiant, "0000", "00", "000"))
    	->endkey(sprintf(self::TYPE_MODEL."-%s-%s-%s-%s", $identifiant, "9999", "99", "999"));
    	return $view->execute($hydrate)->getDatas();
    }

    public function getNextIdentifiantForEtablissementAndDate($identifiant, $date) {
    	$periode = $this->getPeriodeByDate($date);
    	$ids = $this->startkey(self::TYPE_MODEL.'-' . $identifiant . '-'.$periode.'-000')->endkey(self::TYPE_MODEL. '-' . $identifiant . '-'.$periode.'-999')->execute(acCouchdbClient::HYDRATE_ON_DEMAND)->getIds();
    	$last_num = 0;
    	foreach ($ids as $id) {
    		$exploded = explode('-', $id);
    		$num = ($exploded[count($exploded) - 1] * 1);
    		if ($num > $last_num) {
    			$last_num = $num;
    		}
    	}
    	return sprintf("%03d", $last_num + 1);
    }
}
