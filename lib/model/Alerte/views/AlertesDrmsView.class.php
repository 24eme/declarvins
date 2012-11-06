<?php

class AlertesDrmsView extends acCouchdbView
{
	const KEY_ETABLISSEMENT_IDENTIFIANT = 0;
	const KEY_CAMPAGNE_ANNEE = 1;
	const KEY_CAMPAGNE_MOIS = 2;

	public static function getInstance() {

        return acCouchdbManager::getView('alertes', 'drms', 'DRM');
    }

    public function findByCampagne($start_year, $start_month, $end_year, $end_month) {

    	return $this->client->startkey(array($start_year, $start_month))
                    		->endkey(array($end_year, $end_month, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByCampagneAndEtablissement($start_year, $start_month, $end_year, $end_month, $identifiant) {

    	return $this->client->startkey(array($identifiant, $start_year, $start_month))
                    		->endkey(array($identifiant, $end_year, $end_month, array()))
                    		->getView($this->design, $this->view);
    }

    public function findAllByEtablissement($identifiant) {

    	return $this->client->startkey(array($identifiant))
                    		->endkey(array($identifiant, array()))
                    		->getView($this->design, $this->view);
    }

    public function findAll() {

    	return $this->client->getView($this->design, $this->view);
    }
}  