<?php

class AlertesAllView extends acCouchdbView
{
	const KEY_SOUS_TYPE = 0;
	const KEY_ID = 1;

	public static function getInstance() {

        return acCouchdbManager::getView('alertes', 'all', 'Alerte');
    }

    public function findByType($interpro, $type) {
    	return $this->client->startkey(array($interpro, $type))
                    		->endkey(array($interpro, $type, array()))
                    		->getView($this->design, $this->view);
    }

    public function findAll($interpro) {
    	return $this->client->startkey(array($interpro))
                    		->endkey(array($interpro, array()))
                    		->getView($this->design, $this->view);
    }
}  