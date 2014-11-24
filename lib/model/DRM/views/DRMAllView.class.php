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
    
    public function getPrecedenteDrmPeriodeByEtablissement($identifiant, $periode)
    {
    	$drms = $this->findByEtablissement($identifiant)->rows;
    	$precedente = null;
    	foreach ($drms as $drm) {
	    	if ((str_replace('-', '', $drm->key[self::KEY_PERIODE]) < str_replace('-', '', $periode))) {
	    		$precedente = $drm->key[self::KEY_PERIODE];
	    	}
    	}
    	return $precedente;
    }
    
    public function getAllFirstDrm()
    {
    	$drms = $this->client->reduce(false)->getView($this->design, $this->view)->rows;
    	$result = array();
    	foreach ($drms as $drm) {
    		if (!array_key_exists($drm->key[self::KEY_IDENTIFIANT], $result)) {
    			$result[$drm->key[self::KEY_IDENTIFIANT]] = $drm->key[self::KEY_PERIODE];
    		} else {
    			if (str_replace('-', '', $drm->key[self::KEY_PERIODE]) < str_replace('-', '', $result[$drm->key[self::KEY_IDENTIFIANT]])) {
    				$result[$drm->key[self::KEY_IDENTIFIANT]] = $drm->key[self::KEY_PERIODE];
    			}
    		}
    	}
    	return $result;
    }

}  