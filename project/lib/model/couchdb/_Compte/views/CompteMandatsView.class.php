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

    public function findByStatutAndInterpro($statut, $interpro = null, $actif = 1) 
    {
    	$comptes = $this->findByStatut($statut, $actif)->rows;
    	$result = array();
    	foreach ($comptes as $compte) {
    		$interpros = json_decode(json_encode($compte->value), true);
    		if (count($interpros) > 0) {
	  			if ($interpro && in_array($interpro, array_keys($interpros))) {
	  				$result[] = $compte;
	  			}
  			} else {
  				$result[] = $compte;
  			}
    	}
    	return $result;
  	}
}