<?php

class AlertesEtablissementsView extends acCouchdbView
{
	const KEY_STATUT = 0;
	const KEY_IDENTIFIANT = 1;

	public static function getInstance() {

        return acCouchdbManager::getView('alertes', 'etablissements', 'Etablissement');
    }

    public function find() {
    	return $this->client->getView($this->design, $this->view);
    }

    public function findActive() {
    	return $this->client->startkey(array(Etablissement::STATUT_ACTIF))
                    		->endkey(array(Etablissement::STATUT_ACTIF, array()))
                    		->getView($this->design, $this->view);
    }

}  