<?php
class CompteMandatsView extends acCouchdbView
{
	
	const KEY_STATUT = 0;
	const KEY_CONTRAT_VALIDE = 1;
	const KEY_NUMERO_CONTRAT = 2;
	const KEY_NOM = 3;
	const KEY_PRENOM = 4;
	const KEY_LOGIN = 5;
	const KEY_EMAIL = 6;
	const KEY_RAISON_SOCIALE = 7;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('compte', 'mandats', '_Compte');
    }

    public function findAll() 
    {
    	return $this->client->getView($this->design, $this->view);
  	}

    public function findByStatut($statut, $actif = 1) 
    {
    	return $this->client->startkey(array($statut, $actif))
                    ->endkey(array($statut, $actif, array()))->getView($this->design, $this->view);
  	}
}