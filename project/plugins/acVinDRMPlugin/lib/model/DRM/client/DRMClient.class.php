<?php

class DRMClient extends acCouchdbClient {

    const VALIDE_STATUS_EN_COURS = '';
    const VALIDE_STATUS_VALIDEE_ENATTENTE = 'VALIDEE';
    const VALIDE_STATUS_VALIDEE_ENVOYEE = 'ENVOYEE';
    const VALIDE_STATUS_VALIDEE_RECUE = 'RECUE';
    const MODE_DE_SAISIE_PAPIER = 'PAPIER';
    const MODE_DE_SAISIE_DTI = 'DTI';
    const MODE_DE_SAISIE_DTI_PLUS = 'DTI_PLUS';
    const MODE_DE_SAISIE_EDI = 'EDI';
    const MODE_DE_SAISIE_PAPIER_LIBELLE = 'par l\'interprofession (papier)';
    const MODE_DE_SAISIE_DTI_LIBELLE = 'via Declarvins (DTI)';
    const MODE_DE_SAISIE_DTI_PLUS_LIBELLE = 'via l\'import (DTI+)';
    const MODE_DE_SAISIE_EDI_LIBELLE = 'via votre logiciel (EDI)';
    
    const DRM_STATUS_BILAN_VALIDE = 'DRM_STATUS_BILAN_VALIDE';
    const DRM_STATUS_BILAN_A_SAISIR = 'DRM_STATUS_BILAN_A_SAISIR';
    const DRM_STATUS_BILAN_IGP_MANQUANT = 'DRM_STATUS_BILAN_IGP_MANQUANT';
    const DRM_STATUS_BILAN_CONTRAT_MANQUANT = 'DRM_STATUS_BILAN_CONTRAT_MANQUANT';
    const DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT = 'DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT';
    const DRM_STATUS_BILAN_NON_VALIDE = 'DRM_STATUS_BILAN_NON_VALIDE';
    const DRM_STATUS_BILAN_STOCK_EPUISE = 'DRM_STATUS_BILAN_STOCK_EPUISE';

    protected $drm_historiques = array();

    /**
     *
     * @return DRMClient
     */
    public static function getInstance() {
        return acCouchdbManager::getClient("DRM");
    }

    public function buildId($identifiant, $periode, $version = null) {

        return 'DRM-' . $identifiant . '-' . $this->buildPeriodeAndVersion($periode, $version);
    }

    public function buildVersion($rectificative, $modificative) {

        return DRM::buildVersion($rectificative, $modificative);
    }

    public function getRectificative($version) {

        return DRM::buildRectificative($version);
    }

    public function getModificative($version) {

        return DRM::buildModificative($version);
    }

    public function getModesDeSaisie() {
        return array(
            self::MODE_DE_SAISIE_PAPIER => self::MODE_DE_SAISIE_PAPIER_LIBELLE,
            self::MODE_DE_SAISIE_DTI => self::MODE_DE_SAISIE_DTI_LIBELLE,
            self::MODE_DE_SAISIE_EDI => self::MODE_DE_SAISIE_EDI_LIBELLE
        );
    }

    public function getPeriodes($campagne) {
        $periodes = array();
        $periode = $this->getPeriodeDebut($campagne);
        while ($periode != $this->getPeriodeFin($campagne)) {
            $periodes[] = $periode;
            $periode = $this->getPeriodeSuivante($periode);
        }

        $periodes[] = $periode;

        return $periodes;
    }

    public function buildDate($periode) {
        return sprintf('%4d-%02d-%02d', $this->getAnnee($periode), $this->getMois($periode), date("t", $this->getMois($periode)));
    }

    public function getPeriodeDebut($campagne) {

        return date('Y-m', strtotime(ConfigurationClient::getInstance()->getDateDebutCampagne($campagne)));
    }

    public function getPeriodeFin($campagne) {

        return date('Y-m', strtotime(ConfigurationClient::getInstance()->getDateFinCampagne($campagne)));
    }

    public function buildCampagne($periode) {

        return ConfigurationClient::getInstance()->buildCampagne($this->buildDate($periode));
    }

    public function buildPeriode($annee, $mois) {

        return sprintf("%04d-%02d", $annee, $mois);
    }

    public function buildPeriodeAndVersion($periode, $version) {
        if ($version) {
            return sprintf('%s-%s', $periode, $version);
        }

        return $periode;
    }

    public function getAnnee($periode) {

        return preg_replace('/([0-9]{4})-([0-9]{2})/', '$1', $periode);
    }

    public function getMois($periode) {

        return preg_replace('/([0-9]{4})-([0-9]{2})/', '$2', $periode);
    }

    public function getEtablissementByDRMId($drm_id) {
        $tab = explode('-', $drm_id);
        if (isset($tab[1])) {
            return $tab[1];
        }
        return null;
    }

    public function getAnneeByDRMId($drm_id) {
        $tab = explode('-', $drm_id);
        if (isset($tab[2])) {
            return $tab[2];
        }
        return null;
    }

    public function getMoisByDRMId($drm_id) {
        $tab = explode('-', $drm_id);
        if (isset($tab[3])) {
            return $tab[3];
        }
        return null;
    }

    public function getVersionByDRMId($drm_id) {
        $tab = explode('-', $drm_id);
        if (isset($tab[4])) {
            return $tab[4];
        }
        return null;
    }

    public function getPeriodeSuivante($periode) {
        $nextMonth = $this->getMois($periode) + 1;
        $nextYear = $this->getAnnee($periode);

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        return $this->buildPeriode($nextYear, $nextMonth);
    }

    public function getPeriodePrecedente($periode) {
        $prevMonth = $this->getMois($periode) - 1;
        $year = $this->getAnnee($periode);

        if ($prevMonth < 1) {
            $prevMonth = 12;
            $year--;
        }

        return $this->buildPeriode($year, $prevMonth);
    }

    public function getModeDeSaisieLibelle($key) {
        switch ($key) {
            case self::MODE_DE_SAISIE_DTI:
                return self::MODE_DE_SAISIE_DTI_LIBELLE;
                break;
            case self::MODE_DE_SAISIE_DTI_PLUS:
                return self::MODE_DE_SAISIE_DTI_PLUS_LIBELLE;
                break;
            case self::MODE_DE_SAISIE_EDI:
                return self::MODE_DE_SAISIE_EDI_LIBELLE;
                break;
            case self::MODE_DE_SAISIE_PAPIER:
                return self::MODE_DE_SAISIE_PAPIER_LIBELLE;
                break;
            default:
                return 'NR';
                break;
        }
    }

    public function findMasterByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $drms = $this->viewByIdentifiantPeriode($identifiant, $periode);

        foreach ($drms as $id => $drm) {
            return $this->find($id, $hydrate);
        }
        return null;
    }

    public function findMasterByIdentifiantAndPeriodeAndRectificative($identifiant, $periode, $rectificative, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {

        $version = $this->getMasterVersionOfRectificative($identifiant, $periode, $rectificative);

        return $this->find($this->buildId($identifiant, $periode, $version));
    }

    public function findByIdentifiantAndPeriodeAndRectificative($identifiant, $periode, $rectificative, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        $drms = array();
        $rows = $this->viewByIdentifiantPeriodeAndRectificative($identifiant, $periode, $rectificative);
        foreach ($rows as $id => $row) {
            $drms[$id] = $this->find($id);
        }

        return $drms;
    }

    public function getMasterVersionOfRectificative($identifiant, $periode, $rectificative) {
        $drms = $this->viewByIdentifiantPeriodeAndRectificative($identifiant, $periode, $rectificative);

        foreach ($drms as $id => $drm) {

            return $drm[3];
        }

        return null;
    }

    public function findOrCreateByIdentifiantAndPeriode($identifiant, $periode, $hydrate = acCouchdbClient::HYDRATE_DOCUMENT) {
        if ($obj = $this->findMasterByIdentifiantAndPeriode($identifiant, $periode, $hydrate)) {
            return $obj;
        }

        $obj = new DRM();
        $obj->identifiant = $identifiant;
        $obj->periode = $periode;

        return $obj;
    }

    public function viewByIdentifiant($identifiant) {
        $rows = acCouchdbManager::getClient()
                        ->startkey(array($identifiant))
                        ->endkey(array($identifiant, array()))
                        ->reduce(false)
                        ->getView("drm", "all")
                ->rows;

        $drms = array();

        foreach ($rows as $row) {
            $drms[$row->id] = $row->key;
        }

        uksort($drms, array($this, 'sortDrmId'));

        return $drms;
    }

    public function viewByIdentifiantAndCampagne($identifiant, $campagne) {
        $rows = acCouchdbManager::getClient()
                        ->startkey(array($identifiant, $campagne))
                        ->endkey(array($identifiant, $campagne, array()))
                        ->reduce(false)
                        ->getView("drm", "all")
                ->rows;

        $drms = array();

        foreach ($rows as $row) {
            $drms[$row->id] = $row->key;
        }

        uksort($drms, array($this, 'sortDrmId'));

        return $drms;
    }

    protected function viewByIdentifiantPeriode($identifiant, $periode) {
        $campagne = $this->buildCampagne($periode);

        $rows = acCouchdbManager::getClient()
                        ->startkey(array($identifiant, $campagne, $periode))
                        ->endkey(array($identifiant, $campagne, $periode, array()))
                        ->reduce(false)
                        ->getView("drm", "all")
                ->rows;

        $drms = array();

        foreach ($rows as $row) {
            $drms[$row->id] = $row->key;
        }

        uksort($drms, array($this, 'sortDrmId'));
        return $drms;
    }

    protected function viewByIdentifiantPeriodeAndRectificative($identifiant, $periode, $rectificative) {
        $campagne = $this->buildCampagne($periode);
        $beginVersion = $this->buildVersion($rectificative, 0);
        $endVersion = ($beginVersion) ? $this->buildVersion($rectificative, 99) : null;
        $rows = acCouchdbManager::getClient()
                        ->startkey(array($identifiant, $campagne, $periode, $beginVersion))
                        ->endkey(array($identifiant, $campagne, $periode, $endVersion, array()))
                        ->reduce(false)
                        ->getView("drm", "all")
                ->rows;

        $drms = array();

        foreach ($rows as $row) {
            $drms[$row->id] = $row->key;
        }

        uksort($drms, array($this, 'sortDrmId'));

        return $drms;
    }

    public function getDRMHistorique($identifiant) {
        if (!array_key_exists($identifiant, $this->drm_historiques)) {

            $this->drm_historiques[$identifiant] = new DRMHistorique($identifiant);
        }

        return $this->drm_historiques[$identifiant];
    }

    public function createDoc($identifiant, $periode = null) {
        if (!$periode) {
            $periode = $this->getCurrentPeriode();
            $last_drm = $this->getDRMHistorique($identifiant)->getLastDRM();
            if ($last_drm) {
                $periode = $this->getPeriodeSuivante($last_drm->periode);
            }
        }

        return $this->createDocByPeriode($identifiant, $periode);
    }

    public function getLastPeriode($identifiant) {
        $periode = $this->getCurrentPeriode();
        $last_drm = $this->getDRMHistorique($identifiant)->getLastDRM();
        if ($last_drm) {
            $periode = $this->getPeriodeSuivante($last_drm->periode);
        }
        return $periode;
    }

    public function createBlankDoc($identifiant, $periode, $version = null) {
        $drm = new DRM();
        $drm->identifiant = $identifiant;
        $drm->periode = $periode;
        if ($version) {
        	$drm->version = $version;
        }
        return $drm;
    }

    public function createDocByPeriode($identifiant, $periode) {
        $prev_drm = $this->getDRMHistorique($identifiant)->getPreviousDRM($periode);
        if ($prev_drm) {
            $drm = $prev_drm->generateSuivanteByPeriode($periode);
        } else {
            $next_drm = $this->getDRMHistorique($identifiant)->getNextDRM($periode);
            if ($next_drm) {
                $drm = $next_drm->generateSuivanteByPeriode($periode);
            } else {
                $drm = $this->createBlankDoc($identifiant, $periode);
            }
        }
        $drm->mode_de_saisie = self::MODE_DE_SAISIE_DTI;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $drm->mode_de_saisie = self::MODE_DE_SAISIE_PAPIER;
        }
        return $drm;
    }

    public function getCurrentPeriode() {
        $timestamp = strtotime('-1 month');
        return sprintf('%s-%02d', date('Y', $timestamp), date('m', $timestamp));
    }

    public function getUser() {

        return sfContext::getInstance()->getUser();
    }

    public function findAll() {

        return $this->reduce(false)->getView('drm', 'all');
    }

    public function getLibelleFromId($id) {
        if (!$id) {
            return null;
        }

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('Orthographe', 'Date'));
        $origineLibelle = 'DRM de';
        $drmSplited = explode('-', $id);
        $annee = $drmSplited[2];
        $mois = $drmSplited[3];
        $date = $annee . '-' . $mois . '-01';
        $df = format_date($date, 'MMMM yyyy', 'fr_FR');
        return elision($origineLibelle, $df);
    }

    public static function getAllLibellesStatusBilan() {
        return array(self::DRM_STATUS_BILAN_A_SAISIR => "DRM à saisir",
            self::DRM_STATUS_BILAN_VALIDE => "DRM validée",
            self::DRM_STATUS_BILAN_CONTRAT_MANQUANT => "DRM validée avec infos contrats vrac manquantes",
            self::DRM_STATUS_BILAN_IGP_MANQUANT => "DRM validée avec infos IGP manquantes",
            self::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT => "DRM validée avec infos contrats vrac et IGP manquantes",
            self::DRM_STATUS_BILAN_NON_VALIDE => "DRM saisie non validée",
            self::DRM_STATUS_BILAN_STOCK_EPUISE => "Stock épuisé");
    }

    public static function getLibellesForStatusBilan($status) {
        $allLibellesStatusBilan = self::getAllLibellesStatusBilan();
        return $allLibellesStatusBilan[$status];
    }
    
    public function sortDrmId($a, $b) 
    {
    	preg_match('/DRM-([0-9a-zA-Z]*)-([0-9]{4})-([0-9]{2})/', $a, $ma1);
        preg_match('/DRM-([0-9a-zA-Z]*)-([0-9]{4})-([0-9]{2})/', $b, $mb1);
        $hasVersionA = preg_match('/([0-9a-zA-Z\-]*)-(M|R)([0-9]{2})$/', $a, $ma);
        $hasVersionB = preg_match('/([0-9a-zA-Z\-]*)-(M|R)([0-9]{2})$/', $b, $mb);


        if ((!$hasVersionA && !$hasVersionB) || $ma1[2].$ma1[3] != $mb1[2].$mb1[3]) {
                if ($ma1[2].$ma1[3] > $mb1[2].$mb1[3]) {
                        return -1;
                }
                if ($ma1[2].$ma1[3] < $mb1[2].$mb1[3]) {
                        return 1;
                }
                return 0;
        }
        
        if (!$hasVersionA) {
                return 1;
        }
        
        if (!$hasVersionB) {
                return -1;
        }
        return ($ma[3] < $mb[3])? 1 : -1;
    	
    }

}
