<?php

class DRMHistorique {

    protected $identifiant = null;
    protected $drms = null;
    protected $has_drm_process = null;
    protected $last_drm = null;

    const VIEW_INDEX_ETABLISSEMENT = 0;
    const VIEW_CAMPAGNE = 1;
    const VIEW_PERIODE = 2;
    const VIEW_VERSION = 3;
    const VIEW_MODE_DE_DAISIE = 4;
    const VIEW_STATUT = 5;
    const VIEW_STATUT_DOUANE_ENVOI = 6;
    const VIEW_STATUT_DOUANE_ACCUSE = 7;

    public function __construct($identifiant)
    {
        $this->identifiant = $identifiant;

        $this->loadDRMs();
    }

    public function hasDRMInProcess() {
        
        return $this->has_drm_process;
    }

    public function getLastDRM() {
    	if (!$this->last_drm) {
	        foreach($this->drms as $drm) {
	            $this->last_drm = DRMClient::getInstance()->find($drm->_id);
	            break;
	        }
    	}
    	return $this->last_drm;
    }
    
    public function getLastPeriode($with_current = true) {
    	$lastDrm = $this->getLastDRM();
    	if ($lastDrm) {
    		return $lastDrm->periode;
    	}
    	return ($with_current)? $this->getCurrentPeriode() : null;
    }
    
	public function getCurrentPeriode() {
	      $timestamp = strtotime('-1 month');
	      return sprintf('%s-%02d', date('Y', $timestamp), date('m', $timestamp));
  	}

    public function getPreviousDRM($periode) {
        foreach($this->drms as $drm) {
            if ($drm->periode < $periode) {
                
                return DRMClient::getInstance()->find($drm->_id);
            }
        }
    }

    public function getNextDRM($periode) {
        $next_drm = new stdClass();
        $next_drm->_id = null;
        $next_drm->periode = '9999-99';
        foreach($this->drms as $drm) {
            if ($drm->periode < $next_drm->periode) {
                $next_drm = $drm;
            }

            if($drm->periode <= $periode) {
                break;
            }
        }

        if(!$next_drm->_id) {
            return null;
        }

        return DRMClient::getInstance()->find($next_drm->_id);
    }

    protected function loadDRMs() {
        $this->drms = array();

        $drms = DRMClient::getInstance()->viewByIdentifiant($this->identifiant);

        $this->has_drm_process = false;

        foreach($drms as $drm) {
            $key = $drm[self::VIEW_PERIODE].$drm[self::VIEW_VERSION];
            if (array_key_exists($key, $this->drms)) {
                
                continue;
            }

            $this->drms[$key] = $this->build($drm);

            if (!$this->drms[$key]->valide->date_saisie) {
                $this->has_drm_process = true;
            }
        }
    }

    protected function build($ligne) {
        $drm = new stdClass();
        $drm->identifiant = $ligne[self::VIEW_INDEX_ETABLISSEMENT];
        $drm->campagne = $ligne[self::VIEW_CAMPAGNE];
        $drm->periode = $ligne[self::VIEW_PERIODE];
        $drm->version = $ligne[self::VIEW_VERSION];
        $drm->mode_de_saisie = $ligne[self::VIEW_MODE_DE_DAISIE];
        $drm->valide = new stdClass();
        $drm->valide->date_saisie = $ligne[self::VIEW_STATUT];
        $drm->douane = new stdClass();
        $drm->douane->envoi = $ligne[self::VIEW_STATUT_DOUANE_ENVOI];
        $drm->douane->accuse = $ligne[self::VIEW_STATUT_DOUANE_ACCUSE];
        $drm->_id = DRMClient::getInstance()->buildId($drm->identifiant, $drm->periode, $drm->version);

        return $drm;
    }

    public function getDRMs() {

        return $this->drms;
    }

    public function getDRMsByCampagne($campagne) {
        $drms = array();
        foreach($this->getDRMs() as $drm) {
            if ($drm->campagne == $campagne) {
                $drms[$drm->_id] = $drm;
            }
        }
        return $drms;
    }

    public function getDRMsPeriodeByCampagne($campagne) {
        $periodes = array();
        foreach($this->getDRMsByCampagne($campagne) as $drm) {
        	if (!in_array($drm->periode, $periodes)) {
        		$periodes[] = $drm->periode;
        	}
        }
        return $periodes;
    }

    public function getLastDRMByCampagne($campagne) {
    	$d = null;
    	$drms = $this->drms;
    	ksort($drms);
        foreach($drms as $drm) {
            if ($drm->campagne == $campagne) {
               $d = DRMClient::getInstance()->find($drm->_id);
            }
        }
        return $d;
    }

    public function getCampagnes() {
        $campagnes = array();
        foreach($this->getDRMs() as $drm) {
            if (!in_array($drm->campagne, $campagnes)) {
                $campagnes[] = $drm->campagne;
            }
        }
        return $campagnes;
    }

    public function getIdentifiant() {

        return $this->identifiant;
    }

}

