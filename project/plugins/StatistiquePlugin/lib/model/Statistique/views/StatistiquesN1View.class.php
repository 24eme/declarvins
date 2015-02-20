<?php

class StatistiquesN1View extends acCouchdbView
{
	const KEY_REGION = 0;
	const KEY_PERIODE = 1;
	const KEY_STATUT = 2;
	const VALUE_ETABLISSEMENTID = 0;
	const VALUE_ETABLISSEMENT = 1;
	

	public static function getInstance() 
	{
        return acCouchdbManager::getView('statistiques', 'n-1');
    }

    public function findManquantesByPeriode($region, $periode) 
    {
    	$startparams = array($region, $periode, DRMClient::DRM_STATUS_BILAN_A_SAISIR);        
    	$endparams = $startparams;
    	$endparams[] = array();
    	return $this->client->startkey($startparams)
                    		->endkey($endparams)
                    		->getView($this->design, $this->view);
    }
}  