<?php

class EtablissementInterproView extends acCouchdbView
{
	const KEY_SIRET = 0;
	const VALUE_INTERPRO = 0;

	public static function getInstance() {

        return acCouchdbManager::getView('etablissement', 'interpro', 'Etablissement');
    }

    public function findInterproRefBySiret($siret) {

    	$result = $this->client->startkey(array(Etablissement::STATUT_ACTIF, $siret))
                    		->endkey(array(Etablissement::STATUT_ACTIF, $siret, array()))
                    		->getView($this->design, $this->view)->rows;
    	if (!count($result)) {
    		return null;
    	}
    	$result = current($result);
    	return $result->value[self::VALUE_INTERPRO];
    }

}  