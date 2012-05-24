<?php

class drmComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        
        $i = 3;
        foreach ($this->config_certifications as $certification_config) {
            if ($this->drm->exist($certification_config->getHash())) {
            	$certification = $this->drm->get($certification_config->getHash());
            	if ($certification->hasMouvementCheck()) {
	                $this->certifications[$i] = $this->drm->get($certification_config->getHash());
	                $i++;
            	}
            }
        }
        $nbCertifs = count($this->certifications);
        $this->numeros = array(
            'informations' => 1,
            'ajouts_liquidations' => 2,
            'recapitulatif' => 3,
            'vrac' => 3 + $nbCertifs,
            'declaratif' => 4 + $nbCertifs,
            'validation' => 5 + $nbCertifs,
        );
        
        $this->numero = $this->numeros[$this->etape];
        if(isset($this->numeros[$this->drm->etape])) 
            $this->numero_autorise = $this->numeros[$this->drm->etape];
        else 
            $this->numero_autorise = '';
        $this->numero_vrac = $this->numeros['vrac'];
        $this->numero_declaratif = $this->numeros['declaratif'];
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config) {
                if ($this->drm->exist($certification_config->getHash())) {
                    if ($certification->getKey() == $certification_config->getKey()) {
                        break;
                    }
                    $this->numero++;
                }
            }
        }
    }

    public function executeHistoriqueItem() {
        $this->campagne_rectificative = DRMClient::getInstance()->getCampagneAndRectificative($this->drm[DRMHistorique::VIEW_INDEX_ANNEE].'-'.$this->drm[DRMHistorique::VIEW_INDEX_MOIS], $this->drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE]);
        $this->valide = $this->drm[DRMHistorique::VIEW_INDEX_STATUS] && $this->drm[DRMHistorique::VIEW_INDEX_STATUS] > 0;
        $this->titre = $this->drm[DRMHistorique::VIEW_INDEX_ANNEE].'-'.$this->drm[DRMHistorique::VIEW_INDEX_MOIS];
        if($this->drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE]) {
            $this->titre .= ' R'.$this->drm[DRMHistorique::VIEW_INDEX_RECTIFICATIVE];
        }
        $this->derniere = $this->drm[DRMHistorique::DERNIERE];
    }

    public function executeHistoriqueList() {
        if (isset($this->limit)) {
            $this->list = $this->historique->getSliceDrms($this->limit);
        } else {
            $this->list = $this->historique->getDrmsParCampagneCourante();   
        }
        $this->futurDrm = current($this->historique->getFutureDrm());
        //var_dump($this->futurDrm);exit;
        $this->hasNewDrm = false;
        if (CurrentClient::getCurrent()->campagne >= ($this->futurDrm[1].'-'.$this->futurDrm[2]) && !$this->historique->hasDrmInProcess()) {
            $this->hasNewDrm = true;
            if (isset($this->limit)) {
                $this->limit--;
            }
        }
    }

}
