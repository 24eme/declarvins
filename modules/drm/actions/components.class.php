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

    protected function getNewDRM($identifiant) {
        $drm = DRMClient::getInstance()->createDoc($identifiant);

        if($drm && $drm->periode > DRMClient::getInstance()->getCurrentPeriode()) {

            $drm = null;
        }

        if ($drm && DRMClient::getInstance()->getDRMHistorique($identifiant)->hasDRMInProcess()) {
        
            $drm = null;
        }

        if(isset($this->campagne) && $drm->campagne != $this->campagne) {

            $drm = null;
        }

        return $drm;
    }

    public function executeHistoriqueList() {
        $this->drms = array();
        $historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);

        $this->new_drm = $this->getNewDRM($this->etablissement->identifiant);

        if (!isset($this->campagne) && $this->new_drm) {
            $this->campagne = $this->new_drm->campagne;
        } elseif(!isset($this->campagne)) {
            $campagnes = $historique->getCampagnes();
            $this->campagne = $campagnes[0];
        }

        foreach($historique->getDRMsByCampagne($this->campagne) as $key => $drm) {
            $this->drms[$key] = DRMClient::getInstance()->find($key);
        }

    }

    public function executeCampagnes() {
        $this->historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);

        $new_drm = $this->getNewDRM($this->etablissement->identifiant);

        $this->campagnes = $this->historique->getCampagnes();

        if ($new_drm && !in_array($new_drm->campagne, $this->campagnes)) {
            $this->campagnes = array_merge(array($new_drm->campagne), $this->campagnes);
        }

        if (!isset($this->campagne)) {
            $this->campagne = $this->campagnes[0];
        }
    }

}
