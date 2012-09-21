<?php

class VracAllView extends acCouchdbView
{
	const VRAC_VIEW_ETABLISSEMENT = 1;
    const VRAC_VIEW_PRODUIT = 2;
    const VRAC_VIEW_ID = 3;
    const VRAC_VIEW_STATUT = 0;

	public static function getInstance() {

        return acCouchdbManager::getView('vrac', 'all', 'Vrac');
    }
    
	public function findByEtablissement($identifiant) {
		$identifiant = EtablissementClient::getInstance()->getIdentifiant($identifiant);

        return $this->client->startkey(array(VracClient::STATUS_CONTRAT_NONSOLDE,$identifiant))->endkey(array(VracClient::STATUS_CONTRAT_NONSOLDE,$identifiant, array()))->getView($this->design, $this->view);
    }

}  