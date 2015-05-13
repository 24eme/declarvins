<?php

class DouaneAllView extends acCouchdbView
{
	const VALUE_DOUANE_NOM = 0;
	const VALUE_DOUANE_IDENTIFIANT = 1;
	const VALUE_DOUANE_EMAIL = 2;
	const VALUE_DOUANE_STATUT = 3;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('douane', 'all', 'Douane');
    }

	public function findAll() 
	{
      return $this->client->getView($this->design, $this->view);
    }

	public function findActives() 
	{
      return $this->client->startkey(array(Douane::STATUT_ACTIF))
                    	  ->endkey(array(Douane::STATUT_ACTIF, array()))
      				      ->getView($this->design, $this->view);
    }


}  