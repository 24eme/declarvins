<?php

class AlertesAllEtablissementsView extends acCouchdbView
{
	const KEY_INTERPRO = 0;
	const KEY_STATUT = 1;
	const KEY_SOUS_TYPE = 2;
	const KEY_ETABLISSEMENT = 3;
	const KEY_CAMPAGNE = 4;
	const KEY_ID = 5;

	public static function getInstance() {

        return acCouchdbManager::getView('alertes', 'all_etablissements', 'Alerte');
    }

    public function findByEtablissement($interpro, $type, $etablissement) {
    	return $this->client->startkey(array($interpro, Alerte::STATUT_ACTIF, $type, $etablissement))
                    		->endkey(array($interpro, Alerte::STATUT_ACTIF, $type, $etablissement, array()))
                    		->getView($this->design, $this->view);
    }

    public function findByEtablissementAndCampagne($interpro, $type, $etablissement, $campagne) {
    	return $this->client->startkey(array($interpro, Alerte::STATUT_ACTIF, $type, $etablissement, $campagne))
                    		->endkey(array($interpro, Alerte::STATUT_ACTIF, $type, $etablissement, $campagne, array()))
                    		->getView($this->design, $this->view);
    }
}  