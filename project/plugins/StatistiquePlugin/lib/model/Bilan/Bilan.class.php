<?php

/**
 * Model for Bilan
 *
 */
class Bilan extends BaseBilan {

    public function __construct() {
        parent::__construct();
        //MAYBE...
    }

    public function constructId() {
        $this->set('_id', BilanClient::getInstance()->buildId($this->identifiant, $this->type_bilan));
    }

    public function updateEtablissement($etablissement = null) {
    	if (!$etablissement) {
        	$etablissement = EtablissementClient::getInstance()->findByIdentifiant($this->identifiant);
    	}
        $this->remove('etablissement');
        $this->add('etablissement');
        $this->etablissement->nom = $etablissement->nom;
        $this->etablissement->statut = $etablissement->statut;
        $this->etablissement->interpro = $etablissement->interpro;
        $this->etablissement->num_interne = $etablissement->num_interne;
        $this->etablissement->siret = $etablissement->siret;
        $this->etablissement->raison_sociale = $etablissement->raison_sociale;
        $this->etablissement->cni = $etablissement->cni;
        $this->etablissement->cvi = $etablissement->cvi;
        $this->etablissement->no_accises = $etablissement->no_accises;
        $this->etablissement->no_tva_intracommunautaire = $etablissement->no_tva_intracommunautaire;
        $this->etablissement->email = $etablissement->email;
        $this->etablissement->telephone = $etablissement->telephone;
        $this->etablissement->fax = $etablissement->fax;
        $this->etablissement->service_douane = $etablissement->service_douane;
        $this->etablissement->siege->adresse = $etablissement->siege->adresse;
        $this->etablissement->siege->code_postal = $etablissement->siege->code_postal;
        $this->etablissement->siege->commune = $etablissement->siege->commune;
        $this->etablissement->siege->pays = $etablissement->siege->pays;
        if ($etablissement->exist('zones')) {
            $this->etablissement->add('zones', $etablissement->zones);
        }
    }

    public function updateFromDRM($drm, $isFirstCampagne = false) {

        if (!$drm) {
            return;
        }
        if (!$drm->exist('campagne') || !$drm->campagne) {
            return;
        }
        if (!$drm->exist('periode') || !$drm->periode) {
            return;
        }

        $this->periodes->add($drm->periode);
        $this->sortPeriodes();

        $periodeNode = $this->periodes->get($drm->periode);
        $periodeNode->id_drm = $drm->_id;
        $periodeNode->mode_de_saisie = $drm->mode_de_saisie;
        $periodeNode->statut = $drm->getStatutBilan();
        $periodeNode->statut_libelle = $drm->getLibelleBilan();
        if ($drm->exist('declaration') && $drm->declaration && $drm->declaration->exist('total')) {
            $periodeNode->total_fin_de_mois = $drm->declaration->total;
        }
        $this->updateDRMManquantesAndNonSaisiesForCampagne($drm->campagne, $drm->periode, $isFirstCampagne);
    }

    public function updateDRMManquantesAndNonSaisiesForCampagne($campagne, $periode, $isFirstCampagne = false) {

        $all_periodesForCampagne = ConfigurationClient::getInstance()->getPeriodesForCampagne($campagne);
        if ($isFirstCampagne) {
            $periodeForFirstCampagne = array();
            foreach ($all_periodesForCampagne as $periodeForCampagne) {
                if ($periode < $periodeForCampagne) {
                    $periodeForFirstCampagne[] = $periodeForCampagne;
                }
            }
            $all_periodesForCampagne = $periodeForFirstCampagne;
        }

        foreach ($all_periodesForCampagne as $periode) {
            $exist_drm = $this->existDRMForPeriode($periode);
            if (!$this->periodes->exist($periode)) {
                $this->periodes->add($periode);
                $this->sortPeriodes();
            }
            $this->updateDRMManquantesAndNonSaisiesForPeriode($periode, $exist_drm);
        }
    }

    public function updateDRMManquantesAndNonSaisiesForPeriode($periode, $exist_drm = false) {
        if ($exist_drm) {
            return;
        }
        $periodeNode = $this->periodes->get($periode);
        $previousNode = $this->getPreviousBilanPeriodeNode($periode);
        if ($previousNode && $this->isInStatusNonSaisieOrZeroVolume($previousNode)) {
            $periodeNode->statut = DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE;
            $periodeNode->total_fin_de_mois = 0;
        } else {
            $periodeNode->statut = DRMClient::DRM_STATUS_BILAN_A_SAISIR;
            $periodeNode->id_drm = null;
            $periodeNode->total_fin_de_mois = null;
        }
        $periodeNode->statut_libelle = DRMClient::getLibellesForStatusBilan($periodeNode->statut);
    }

    public function isInStatusNonSaisieOrZeroVolume($previousNode) {
        return $previousNode->statut == DRMClient::DRM_STATUS_BILAN_STOCK_EPUISE
         || ($previousNode->statut == DRMClient::DRM_STATUS_BILAN_VALIDE
              && !is_null($previousNode->total_fin_de_mois) && ($previousNode->total_fin_de_mois == 0) );
    }

    private function getPreviousBilanPeriodeNode($periode) {
        return $this->periodes->get($periode)->getPreviousSister();
    }

    private function sortPeriodes() {
        $periodeArray = $this->periodes->toArray(true, false);
        ksort($periodeArray);
        $this->remove('periodes');
        $this->add('periodes', $periodeArray);
    }

    private function existDRMForPeriode($periode) {
        $drm_client = DRMClient::getInstance();
        return !is_null($drm_client->find($drm_client->buildId($this->identifiant, $periode)));
    }

}
