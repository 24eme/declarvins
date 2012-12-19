<?php

class drmComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        
        $i = 3;
        foreach ($this->config_certifications as $certification_config) {
            if ($this->drm->exist($certification_config->getHash())) {
            	$certif = $this->drm->get($certification_config->getHash());
            	if ($certif->hasMouvementCheck()) {
	                $this->certifications[$i] = $this->drm->get($certification_config->getHash());
	                $i++;
            	}
            }
        }
        $nbCertifs = count($this->certifications);
        if (count($this->drm->getDetailsAvecVrac()) > 0) {
	        $this->numeros = array(
	            'informations' => 1,
	            'ajouts_liquidations' => 2,
	            'recapitulatif' => 3,
	            'vrac' => 3 + $nbCertifs,
	            'declaratif' => 4 + $nbCertifs,
	            'validation' => 5 + $nbCertifs,
	        );
        } else {
	        $this->numeros = array(
	            'informations' => 1,
	            'ajouts_liquidations' => 2,
	            'recapitulatif' => 3,
	            'declaratif' => 3 + $nbCertifs,
	            'validation' => 4 + $nbCertifs,
	        );        	
        }
        
        $this->numero = $this->numeros[$this->etape];
        if(isset($this->numeros[$this->drm->etape])) 
            $this->numero_autorise = $this->numeros[$this->drm->etape];
        else 
            $this->numero_autorise = '';
        $this->numero_vrac = (isset($this->numeros['vrac']))? $this->numeros['vrac'] : null;
        $this->numero_declaratif = $this->numeros['declaratif'];
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config) {
                if ($this->drm->exist($certification_config->getHash())) {
                    if ($this->certification == $certification_config->getKey()) {
                        break;
                    }
                    $this->numero++;
                }
            }
        }
    }

    protected function getNewDRM($historique, $identifiant) {
        if ($this->hasNewDRM($historique, $identifiant)) {
        	return DRMClient::getInstance()->createDoc($identifiant);
        }
        return null;
    }

    protected function hasNewDRM($historique, $identifiant) {
        if($historique->getLastPeriode(false) >= $historique->getCurrentPeriode()) {
            return false;
        }
        if ($historique->hasDRMInProcess()) {
            return false;
        }
        if(isset($this->campagne) && $this->campagne && DRMClient::getInstance()->buildCampagne($historique->getLastPeriode()) != $this->campagne) {
            return false;
        }
        return true;
    }

    public function executeHistoriqueList() {
        $this->drms = array();
        $this->campagne = null;
        $historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);
        $this->new_drm = $this->getNewDRM($historique, $this->etablissement->identifiant);
		
        if (!isset($this->campagne) && $this->new_drm) {
            $this->campagne = $this->new_drm->campagne;
        } elseif(!isset($this->campagne) || !$this->campagne) {
            $campagnes = $historique->getCampagnes();
            if ($campagnes) {
            	$this->campagne = $campagnes[0];
            }
        }
        foreach($historique->getDRMsByCampagne($this->campagne) as $key => $drm) {
            $this->drms[$key] = DRMClient::getInstance()->find($key);
        }

    }

    public function executeCampagnes() {
        $this->historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);

        $new_drm = $this->hasNewDRM($this->historique, $this->etablissement->identifiant);

        $this->campagnes = $this->historique->getCampagnes();
		$lastCampagne = DRMClient::getInstance()->buildCampagne($this->historique->getLastPeriode());
		
        if ($new_drm && !in_array($lastCampagne, $this->campagnes)) {
            $this->campagnes = array_merge(array($lastCampagne), $this->campagnes);
        }

        if (!isset($this->campagne) && $this->campagnes) {
            $this->campagne = $this->campagnes[0];
        }
    }

}
