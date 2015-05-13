<?php

class AlertesAllView extends acCouchdbView
{
	const KEY_INTERPRO = 0;
	const KEY_STATUT = 1;
	const KEY_SOUS_TYPE = 2;
	const KEY_CAMPAGNE = 3;
	const KEY_ID = 4;

	public static function getInstance() {

        return acCouchdbManager::getView('alertes', 'all', 'Alerte');
    }

    public function findAll($interpro, $type) {
    	return $this->client->startkey(array($interpro, Alerte::STATUT_ACTIF, $type))
                    		->endkey(array($interpro, Alerte::STATUT_ACTIF, $type, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByCampagne($interpro, $type, $campagne) {
    	return $this->client->startkey(array($interpro, Alerte::STATUT_ACTIF, $type, $campagne))
                    		->endkey(array($interpro, Alerte::STATUT_ACTIF, $type, $campagne, array()))
                    		->getView($this->design, $this->view);
    }

}  