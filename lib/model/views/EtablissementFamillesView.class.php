<?php

class EtablissementFamillesView extends acCouchdbView
{
	const KEY_FAMILLE = 0;
	const KEY_COMMUNE = 1;
	const KEY_CODE_POSTAL = 2;
	const KEY_CVI = 3;
	const KEY_NOM = 4;
	const KEY_IDENTIFIANT = 5;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'familles', 'Etablissement');
    }

    public function findByFamille($famille) {

    	return $this->client->startkey(array($famille))
                    		->endkey(array($famille, array()))
                    		->getView($this->design, $this->view);
    }

}  