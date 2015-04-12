<?php

class StatistiquesBilanView extends acCouchdbView
{
	const KEY_REGION = 0;
	const KEY_ID = 1;

	public static function getInstance() 
	{
        return acCouchdbManager::getView('statistiques', 'bilan');
    }

    public function findDrmByRegion($region, $statut = Etablissement::STATUT_ACTIF) 
    {
    	$startparams = array($region, $statut);        
    	$endparams = $startparams;
    	$endparams[] = array();
    	return $this->client->startkey($startparams)
                    		->endkey($endparams)
                    		->getView($this->design, $this->view);
    }
}  