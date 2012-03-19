<?php

class drmComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
        $this->certifications = array();
        $this->certificationsLibelle = array();
        
        $i = 3;
        foreach ($this->config_certifications as $certification => $produit) {
            if ($this->drm->produits->exist($certification)) {
                $this->certifications[$i] = $certification;
                $this->certificationsLibelle[$i] = ConfigurationClient::getCurrent()->declaration->certifications->get($certification)->libelle;
                $i++;
            }
        }
        
        $this->numeros = array(
            'informations' => 1,
            'ajouts-liquidations' => 2,
            'recapitulatif' => 3,
            'vrac' => 3 + count($this->certifications),
            'validation' => 4 + count($this->certifications),
        );
        
        $this->numero = $this->numeros[$this->etape];
        $this->numero_validation = $this->numeros['validation'];
        $this->numero_vrac = $this->numeros['vrac'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification => $produit) {
                if ($this->drm->produits->exist($certification))
                    if ($certification == $this->certification) {
                        break;
                    }
                $this->numero++;
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
            $this->list = $this->historique->getDrmsParAnneeCourante();   
        }
        $this->futurDrm = current($this->historique->getFutureDrm());
        $this->hasNewDrm = false;
        if (CurrentClient::getCurrent()->campagne >= ($this->futurDrm[1].'-'.$this->futurDrm[2]) && !$this->historique->hasDrmInProcess()) {
            $this->hasNewDrm = true;
            if (isset($this->limit)) {
                $this->limit--;
            }
        }
    }

}
