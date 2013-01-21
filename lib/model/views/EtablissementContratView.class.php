<?php

class EtablissementContratView extends acCouchdbView
{
	const CONTRAT_NUMERO = 0;
	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'contrat', 'Etablissement');
    }

    public function findByContrat($contrat) {

    	return $this->client->startkey(array($contrat))
                    		->endkey(array($contrat, array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
    }

}  