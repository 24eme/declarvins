<?php

class EtablissementIdentifiantView extends acCouchdbView
{
	const KEY_IDENTIFIANT = 0;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'identifiant', 'Etablissement');
    }

    public function findByIdentifiant($identifiant) {

    	return $this->client->startkey(array($identifiant))
                    		->endkey(array($identifiant, array()))
                    		->getView($this->design, $this->view);
    }

}  