<?php

class drmComponents extends sfComponents {

    public function executeEtapes() {
        $this->config_certifications = ConfigurationClient::getCurrent()->getCertifications();
        $this->certifications = array();

        $i = 3;
        foreach ($this->config_certifications as $certification_config => $val) {
            if ($this->drm->declaration->certifications->exist($certification_config)) {
                $certif = $this->drm->declaration->certifications->get($certification_config);
                if ($certif->hasMouvementCheck() && count($certif->genres) > 0) {
                    $this->certifications[$i] = $certif;
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
                'crd' => 4 + $nbCertifs,
                'declaratif' => 5 + $nbCertifs,
                'validation' => 6 + $nbCertifs,
            );
        } else {
            $this->numeros = array(
                'informations' => 1,
                'ajouts_liquidations' => 2,
                'recapitulatif' => 3,
                'crd' => 3 + $nbCertifs,
                'declaratif' => 4 + $nbCertifs,
                'validation' => 5 + $nbCertifs,
            );
        }
        
;
        $etablissement = $this->drm->getEtablissementObject();
        if (!$etablissement->isTransmissionCiel()) {
            if ($etablissement->famille == EtablissementFamilles::FAMILLE_NEGOCIANT && $etablissement->sous_famille != EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR) {
                
            } else {
            	unset($this->numeros['crd']);
            	$this->numeros['declaratif'] = $this->numeros['declaratif'] - 1;
            	$this->numeros['validation'] = $this->numeros['validation'] - 1;
            }
        }
        
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	unset($this->numeros['declaratif'], $this->numeros['crd']);
        	$this->numeros['validation'] = $this->numeros['validation'] - 2;
        }

        if (isset($this->numeros[$this->drm->etape]))
            $this->numero_autorise = $this->numeros[$this->drm->etape];
        else
            $this->numero_autorise = '';

        $this->numero = $this->numeros[$this->etape];
        $this->numero_vrac = (isset($this->numeros['vrac'])) ? $this->numeros['vrac'] : null;
        $this->numero_crd = (isset($this->numeros['crd'])) ? $this->numeros['crd'] : null;
        $this->numero_declaratif = (isset($this->numeros['declaratif'])) ? $this->numeros['declaratif'] : null;
        $this->numero_validation = $this->numeros['validation'];

        if ($this->etape == 'recapitulatif') {
            foreach ($this->config_certifications as $certification_config => $val) {
                if ($this->drm->declaration->certifications->exist($certification_config)){
                $certif = $this->drm->declaration->certifications->get($certification_config);
                   if($certif->hasMouvementCheck() && count($certif->genres) > 0) {
                        if ($this->certification == $certification_config) {
                            break;
                        }
                        $this->numero++;
                       
                   }
                }
            }
        }
    }

    protected function getNewDRM($historique, $identifiant) {
        if ($this->hasNewDRM($historique, $identifiant)) {
            $drm = DRMClient::getInstance()->createDoc($identifiant);
            return $drm;
        }
        return null;
    }

    protected function hasNewDRM($historique, $identifiant) {
        /*if ($historique->getLastPeriode(false) >= $historique->getCurrentPeriode()) {
            return false;
        }*/
    	$last = $historique->getLastDRM();
    	$lastCiel = ($last)? $last->getOrAdd('ciel') : null;
        if ($historique->hasDRMInProcess()) {
            return false;
        }
        if ($lastCiel && $lastCiel->isTransfere() && !$lastCiel->isValide()) {
        	return false;
        }
        if (isset($this->campagne) && $this->campagne && DRMClient::getInstance()->buildCampagne($historique->getLastPeriode()) != $this->campagne) {
            return false;
        }
        return true;
    }

    public function executeHistoriqueList() {
        $this->drms = array();
        $this->formImport = new UploadCSVForm();
        $historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);
        $this->new_drm = ($this->etablissement->statut != Etablissement::STATUT_ARCHIVE) ? $this->getNewDRM($historique, $this->etablissement->identifiant) : null;
        //$this->limit = 1;

        if ((!isset($this->campagne) || !$this->campagne) && $this->new_drm) {
            $this->campagne = $this->new_drm->campagne;
        } elseif (!isset($this->campagne) || !$this->campagne) {
            $campagnes = $historique->getCampagnes();
            if ($campagnes) {
                $this->campagne = $campagnes[0];
            }
        }
        foreach ($historique->getDRMsByCampagne($this->campagne) as $key => $infos) {
            if ($drm = DRMClient::getInstance()->find($key)) {
                if ($drm->isMaster()) {
                    $this->drms[$key] = $drm;
                }
            }
        }
    }

    public function executeCampagnes() {
        $this->historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);

        $new_drm = $this->getNewDRM($this->historique, $this->etablissement->identifiant);

        $this->campagnes = $this->historique->getCampagnes();
        if ($new_drm) {
            $lastCampagne = $new_drm->campagne;
        } else {
            $lastCampagne = DRMClient::getInstance()->buildCampagne($this->historique->getLastPeriode());
        }

        if ($new_drm && !in_array($lastCampagne, $this->campagnes)) {
            $this->campagnes = array_merge(array($lastCampagne), $this->campagnes);
        }

        if (!isset($this->campagne) && $this->campagnes) {
            $this->campagne = $this->campagnes[0];
        }
    }

}
