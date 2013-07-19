<?php
class DRMAllView extends acCouchdbView
{
	const KEY_IDENTIFIANT = 0;
	const KEY_CAMPAGNE = 1;
	const KEY_PERIODE = 2;
	const KEY_VERSION = 3;
	const KEY_MODE_DE_SAISIE = 4;
	const KEY_DATE_SAISIE = 5;
	const KEY_DATE_DOUANE_ENVOI = 6;
	const KEY_DATE_DOUANE_ACCUSE = 7;
	

	public static function getInstance() 
	{
        return acCouchdbManager::getView('drm', 'all', 'DRM');
    }

    public function findByEtablissement($identifiant) 
    {
      	return $this->client->startkey(array($identifiant))
                    		->endkey(array($identifiant, array()))
                    		->reduce(false)
                    		->getView($this->design, $this->view);
    }
    
    public function getFirstDrmPeriodeByEtablissement($identifiant, $defaut = null)
    {
    	$drms = $this->findByEtablissement($identifiant)->rows;
    	foreach ($drms as $drm) {
    		if (!$defaut || (str_replace('-', '', $drm->key[self::KEY_PERIODE]) < str_replace('-', '', $defaut))) {
    			$defaut = $drm->key[self::KEY_PERIODE];
    		}
    	}
    	return $defaut;
    }

}  