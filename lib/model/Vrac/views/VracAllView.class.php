<?php

class VracAllView extends acCouchdbView
{
	const VRAC_VIEW_ETABLISSEMENT = 0;
    const VRAC_VIEW_PRODUIT = 1;
    const VRAC_VIEW_ID = 2;
    const VRAC_VIEW_STATUT = 3;

	public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'all', 'Vrac');
    }
    
	public function findByEtablissement($identifiant) {
		$identifiant = EtablissementClient::getInstance()->getIdentifiant($identifiant);

        return $this->client->startkey(array($identifiant))->endkey(array($identifiant, array()))->getView($this->design, $this->view);
    }

}  