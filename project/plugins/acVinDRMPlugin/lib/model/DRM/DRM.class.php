<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM implements InterfaceMouvementDocument, InterfaceVersionDocument, InterfaceDRMExportable {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';

    protected $mouvement_document = null;
    protected $version_document = null;
    protected $suivante = null;

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
        $config_certifications = ConfigurationClient::getCurrent()->getCertifications();
        foreach ($config_certifications as $certification => $config_certification) {
            $this->declaration->certifications->add($certification);
        }
    }

    public function __clone() {
        parent::__clone();
        $this->initDocuments();
    }

    protected function initDocuments() {
        $this->mouvement_document = new MouvementDocument($this);
        $this->version_document = new VersionDocument($this);
    }

    public function constructId() {

        $this->set('_id', DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $this->version));
    }

    public function getPeriodeAndVersion() {

        return DRMClient::getInstance()->buildPeriodeAndVersion($this->periode, $this->version);
    }

    public function getMois() {

        return DRMClient::getInstance()->getMois($this->periode);
    }

    public function getAnnee() {

        return DRMClient::getInstance()->getAnnee($this->periode);
    }

    public function getDate() {

        return DRMClient::getInstance()->buildDate($this->periode);
    }

    public function setPeriode($periode) {
        $this->campagne = DRMClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getProduit($hash, $labels = array()) {
        if (!$this->exist($hash)) {
            return false;
        }

        return $this->get($hash)->details->getProduit($labels);
    }
    
    public function payerReport()
    {
    	$this->declaratif->paiement->douane->report_paye = 1;
    }

    public function addProduit($hash, $labels = array()) {
    	if (!is_array($labels)) {
    		$labels = array($labels);
    	}
        if ($p = $this->getProduit($hash, $labels)) {
            return $p;
        }
        $detail = $this->getOrAdd($hash)->details->addProduit($labels);
        $detail->updateVolumeBloque();
        return $detail;
    }
    
    public function addCrd($categorie, $type, $centilisation, $stock = 0)
    {
    	$idCrd = DRMCrd::makeId($categorie, $type, $centilisation);
    	$crd = $this->crds->getOrAdd($idCrd);
    	if (!$crd->libelle) {
    		$crd->addCrd($categorie, $type, $centilisation, $stock);
    	}
    	return $crd;
    }

    public function getDepartement() {
        if ($this->declarant->siege->code_postal) {
            return substr($this->declarant->siege->code_postal, 0, 2);
        }

        return null;
    }

    public function getModeDeSaisieLibelle() {

        return DRMClient::getInstance()->getModeDeSaisieLibelle($this->mode_de_saisie);
    }

    public function getDetails() {

        return $this->declaration->getProduits();
    }

    public function getProduitsCepages() {

        return $this->declaration->getProduitsCepages();
    }

    public function getDetailsAvecVrac() {
        $details = array();
        foreach ($this->getDetails() as $d) {
            if (($d->sorties->vrac && $d->canHaveVrac()) || count($d->vrac->toArray()) > 0) {
                $details[] = $d;
            }
        }

        return $details;
    }

    public function generateSuivante() {

        return $this->generateSuivanteByPeriode(DRMClient::getInstance()->getPeriodeSuivante($this->periode));
    }

    public function generateSuivanteByPeriode($periode) {
        $is_just_the_next_periode = (DRMClient::getInstance()->getPeriodeSuivante($this->periode) == $periode);
        $keepStock = ($periode > $this->periode);

        $drm_suivante = clone $this;
        $drm_suivante->periode = $periode;
        $drm_suivante->init(array('keepStock' => $keepStock, 'next_campagne' => DRMClient::getInstance()->buildCampagne($periode)));
        $drm_suivante->update();

        if ($is_just_the_next_periode) {
            $drm_suivante->precedente = $this->_id;
        }
        return $drm_suivante;
    }

    public function init($params = array()) {
        parent::init($params);
        $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
        $nextCampagne = isset($params['next_campagne']) ? $params['next_campagne'] : $this->campagne;
        $this->remove('douane');
        $this->add('douane');
        $this->remove('declarant');
        $this->add('declarant');
        $this->remove('editeurs');
        $this->add('editeurs');
        $this->remove('droits');
        $this->add('droits');
        if ($this->exist('manquants')) {
        	$this->remove('manquants');
        	$this->add('manquants');
        }

        $this->version = null;
        $this->raison_rectificative = null;
        $this->etape = null;
        $this->identifiant_drm_historique = null;
        $this->identifiant_ivse = null;

        if (!$keepStock || ($nextCampagne != $this->campagne)) {
            $this->declaratif->adhesion_emcs_gamma = null;
            $this->declaratif->paiement->douane->frequence = null;
            $this->declaratif->paiement->douane->moyen = null;
            $this->declaratif->paiement->douane->report_paye = null;
            $this->declaratif->paiement->cvo->frequence = null;
            $this->declaratif->paiement->cvo->moyen = null;
            $this->declaratif->paiement->cvo->report_paye = null;
            $this->declaratif->caution->dispense = null;
            $this->declaratif->caution->organisme = null;
        }
        $this->declaratif->defaut_apurement = null;
        $this->declaratif->daa->debut = null;
        $this->declaratif->daa->fin = null;
        $this->declaratif->dsa->debut = null;
        $this->declaratif->dsa->fin = null;
        if ($this->declaratif->exist('statistiques')) {
                $this->declaratif->remove('statistiques');
                $this->declaratif->add('statistiques');
        }
        if ($this->declaratif->exist('rna')) {
                $this->declaratif->remove('rna');
                $this->declaratif->add('rna');
        }

        $this->commentaires = null;
        
        $this->initCrds();

        $this->devalide();
    }
    
    public function initCrds()
    {
    	foreach ($this->crds as $crd) {
    		$crd->initCrd();
    	}
    }

    public function setDroits() {
        $this->remove('droits');
        $this->add('droits');
        foreach ($this->getDetails() as $detail) {
            $droitCvo = $detail->getDroit(DRMDroits::DROIT_CVO);
            $droitDouane = $detail->getDroit(DRMDroits::DROIT_DOUANE);
            $mergeSorties = array();
            $mergeEntrees = array();
            if ($detail->interpro == Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID) {
                $mergeSorties = DRMDroits::getDroitSortiesInterRhone();
                $mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
            }
            if ($droitCvo) {
                $this->droits->getOrAdd(DRMDroits::DROIT_CVO)->getOrAdd($droitCvo->code)->integreVolume($detail->sommeLignes(DRMDroits::getDroitSorties()), $detail->sommeLignes(DRMDroits::getDroitEntrees()), $droitCvo->taux, 0, $droitCvo->libelle);
            }
            if ($droitDouane) {
                $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE)->getOrAdd($droitDouane->code)->integreVolume($detail->sommeLignes(DRMDroits::getDouaneDroitSorties()), $detail->sommeLignes(DRMDroits::getDouaneDroitEntrees()), $droitDouane->taux, $this->getReportByDroit(DRMDroits::DROIT_DOUANE, $droitDouane->code), $droitDouane->libelle);
                $codeTotal = DRMDroitsCirculation::getCorrespondanceCode($droitDouane->code).'_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL;
                $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE)->getOrAdd($codeTotal)->integreVolume($detail->sommeLignes(DRMDroits::getDouaneDroitSorties()), $detail->sommeLignes(DRMDroits::getDouaneDroitEntrees()), $droitDouane->taux, $this->getReportByDroit(DRMDroits::DROIT_DOUANE, $codeTotal), $codeTotal);
            }
        }
        $douanes = $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE);
        foreach ($douanes as $k => $douane) {
        	$round = (preg_match('/\_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL.'/', $k))? 0 : 2;
        	$douane->total = round($douane->total, $round);
        	if ($douane->report) {
        		$douane->report = round($douane->report, $round);
        	}
        	if ($douane->cumul) {
        		$douane->cumul = round($douane->cumul, $round);
        	}
        }
    }
	public function isNouvelleCampagne() {
		if ($this->getMois() == 8) {
			return true;
		}
		return false;
	}
    public function getReportByDroit($type, $droit) {
    	if ($this->isNouvelleCampagne()) {
    		return 0;
    	}
    	if ($this->declaratif->paiement->get($type)->exist('report_paye') && $this->declaratif->paiement->get($type)->get('report_paye')) {
    		return 0;
    	}
        $drmPrecedente = $this->getPrecedente();
        if ($drmPrecedente && !$drmPrecedente->isNew()) {
            if ($drmPrecedente->droits->get($type)->exist($droit)) {
                return $drmPrecedente->droits->get($type)->get($droit)->cumul;
            }
        }
        return 0;
    }

    public function detailHasMouvementCheck() {
        foreach ($this->getDetails() as $d) {
            if ($d->hasMouvementCheck()) {
                return true;
            }
        }

        return false;
    }

    public function getEtablissement() {
        if (!$this->identifiant) {
            throw new Exception('pas d\'établissement saisi pour ' . $this->_id);
        }

        $e = EtablissementClient::getInstance()->retrieveById($this->identifiant);

        if (!$e) {
            throw new Exception('pas d\'établissement correspondant à ' . $this->identifiant);
        }

        return $e;
    }
    
    public function getEtablissementObject() {
    	return $this->getEtablissement();
    }

    public function setEtablissementInformations($etablissement = null) {
        if (!$etablissement) {
            $etablissement = $this->getEtablissement();
        }
        $this->declarant->nom = $etablissement->nom;
        $this->declarant->raison_sociale = $etablissement->raison_sociale;
        $this->declarant->siret = $etablissement->siret;
        $this->declarant->cni = $etablissement->cni;
        $this->declarant->cvi = $etablissement->cvi;
        $this->declarant->siege->adresse = $etablissement->siege->adresse;
        $this->declarant->siege->code_postal = $etablissement->siege->code_postal;
        $this->declarant->siege->commune = $etablissement->siege->commune;
        $this->declarant->siege->pays = $etablissement->siege->pays;
        $this->declarant->comptabilite->adresse = $etablissement->comptabilite->adresse;
        $this->declarant->comptabilite->code_postal = $etablissement->comptabilite->code_postal;
        $this->declarant->comptabilite->commune = $etablissement->comptabilite->commune;
        $this->declarant->comptabilite->pays = $etablissement->comptabilite->pays;
        $this->declarant->no_accises = $etablissement->no_accises;
        $this->declarant->no_tva_intracommunautaire = $etablissement->no_tva_intracommunautaire;
        $this->declarant->email = $etablissement->email;
        $this->declarant->telephone = $etablissement->telephone;
        $this->declarant->fax = $etablissement->fax;
        $this->declarant->famille = $etablissement->famille;
        $this->declarant->sous_famille = $etablissement->sous_famille;
        $this->declarant->service_douane = $etablissement->service_douane;
    }

    public function getHistorique() {

        return $this->store('historique', array($this, 'getHistoriqueAbstract'));
    }

    public function getPrecedente() {
        if ($this->exist('precedente') && $this->_get('precedente')) {
            return DRMClient::getInstance()->find($this->_get('precedente'));
        }
    	$periode = DRMClient::getInstance()->getPeriodePrecedente($this->periode);
    	$campagne = DRMClient::getInstance()->buildCampagne($periode);
    	if ($campagne != $this->campagne) {
    		return new DRM();
    	}
    	if ($precente = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode)) {
    		return $precente;
    	}
        return new DRM();
    }

    public function getSuivante() {
        if (is_null($this->suivante)) {
            $periode = DRMClient::getInstance()->getPeriodeSuivante($this->periode);
            $campagne = DRMClient::getInstance()->buildCampagne($periode);
            if ($campagne != $this->campagne) {
                return null;
            }
            $this->suivante = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode);
        }

        return $this->suivante;
    }

    public function isSuivanteCoherente() {
        $drm_suivante = $this->getSuivante();

        if (!$drm_suivante) {

            return true;
        }

        if ($this->validation()->hasError('stock')) {

            return false;
        }

        if ($this->droits->douane->getCumul() != $drm_suivante->droits->douane->getCumul()) {

            return false;
        }

        return false;
    }

    public function devalide() {
        $this->etape = null;
        $this->valide->identifiant = null;
        $this->valide->date_saisie = null;
        $this->valide->date_signee = null;
    }

    public function isValidee() {

        return ($this->valide->date_saisie);
    }

    public function validate($options = array()) {
        if(in_array('onlyUpdateMouvements', $options))
        {
            $this->generateMouvements();
            return;
        }
        $this->update();

        if ($this->hasApurementPossible()) {
            $this->apurement_possible = 1;
        }

        if ($next_drm = $this->getSuivante()) {
            $next_drm->precedente = $this->_id;
            $next_drm->save();
        }
        
        if (!$this->hasDroitsAcquittes()) {
        	$this->cleanVolumesAcquittes();
        }

        $this->storeIdentifiant($options);
        $this->storeDates();
        $this->storeDroits($options);
        $this->setInterpros();
    	if ($this->hasVersion()) {
        	if ($previous = $this->getMother()) {
            	$previous->updateVracVersion();
            }
        }
        $this->updateVrac();
        $this->updateCrds();
        $this->setEtablissementInformations();
         
        $this->generateMouvements();
        
        if ($this->getSuivante() && $this->isSuivanteCoherente()) {
            $this->getSuivante()->precedente = $this->get('_id');
            $this->getSuivante()->save();
        }
        $this->storeReferente();
        
    }
    
    public function isTeledeclare()
    {
    	return ($this->mode_de_saisie == DRMClient::MODE_DE_SAISIE_DTI);
    }

    public function storeReferente() {
        $drm_ref = null;
        if ($this->getPreviousVersion()) {
            $drm_ref = $this->getMother();
        } else {
            $id_drm_ref = DRMClient::getInstance()->buildId($this->identifiant, $this->periode);
            if ($id_drm_ref != $this->_id) {
                $drm_ref = DRMClient::getInstance()->find($id_drm_ref);
            }
        }
        $this->add('referente', 1);
        if (!$drm_ref) {
            return;
        }
        $drm_ref->add('referente', 0);
        $drm_ref->save();
    }

    public function getReferente() {
        if (!$this->exist('referente')) {
            return 1;
        }
        return $this->exist('referente');
    }

    public function storeDroits($options) {
        if (!isset($options['no_droits']) || !$options['no_droits']) {
            $this->setDroits();
        }
    }

    public function storeIdentifiant($options) {
        $identifiant = $this->identifiant;

        if ($options && is_array($options)) {
            if (isset($options['identifiant']))
                $identifiant = $options['identifiant'];
        }

        $this->valide->identifiant = $identifiant;
    }

    public function storeDates() {
        if (!$this->valide->date_saisie) {
            $this->valide->add('date_saisie', date('c'));
        }

        if (!$this->valide->date_signee) {
            $this->valide->add('date_signee', date('c'));
        }
    }
	public function cleanVolumesAcquittes()
	{
		foreach ($this->getDetails() as $detail) {
			$detail->acq_total_debut_mois = null;
			$detail->acq_total_entrees = null;
			$detail->acq_total_sorties = null;
			$detail->acq_total = null;
			$detail->entrees->acq_achat = null;
			$detail->entrees->acq_autres = null;
			$detail->sorties->acq_crd = null;
			$detail->sorties->acq_replacement = null;
			$detail->sorties->acq_autres = null;
		}
	}
    public function updateVrac() {
        foreach ($this->getDetails() as $detail) {
            foreach ($detail->vrac as $numero => $vrac) {
                $volume = $vrac->volume;
                $contrat = VracClient::getInstance()->findByNumContrat($numero);
                $contrat->integreVolumeEnleve($volume);
                $enlevements = $contrat->getOrAdd('enlevements');
                if ($this->hasVersion()) {
                	if ($previous = $this->getMother()) {
                		if ($enlevements->exist($previous->_id)) {
                			$enlevements->remove($previous->_id);
                		}
                	}
                }
                $drm = $enlevements->getOrAdd($this->_id);
                $drm->add('volume', $volume);
                $contrat->save();
            }
        }
    }
    
    public function updateCrds() {
    	foreach ($this->crds as $crd) {
    		$crd->updateStocks();
    	}
    }

    public function updateVracVersion() {
        foreach ($this->getDetails() as $detail) {
            foreach ($detail->vrac as $numero => $vrac) {
                $volume = $vrac->volume;
                if ($contrat = VracClient::getInstance()->findByNumContrat($numero)) {
                    if ($contrat->isSolde()) {
                        $contrat->desolder();
                    }
                    $contrat->soustraitVolumeEnleve($volume);
                    $enlevements = $contrat->getOrAdd('enlevements');
                	$drm = $enlevements->getOrAdd($this->_id);
                	$drm->add('volume', $volume);
                    $contrat->save(false);
                }
            }
        }
    }

    public function updateProduitsDiponibles() {
        $delete = false;
        foreach ($this->getDetails() as $detail) {
            $cvo = $detail->getDroit(ConfigurationProduit::NOEUD_DROIT_CVO);
            if ($cvo && $cvo->taux < 0) {
                $objectToDelete = $detail->cascadingDelete();
                $objectToDelete->delete();
                $delete = true;
            }
        }
        if ($delete) {
            $this->update();
        }
    }

    public function setInterpros() {
        $details = $this->getDetails();
        foreach ($details as $detail) {
            if ($detail->interpro && !in_array($detail->interpro, $this->interpros->toArray())) {
                $this->interpros->add(null, $detail->interpro);
            }
        }
    }

    protected function preSave() {
        if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->periode)) {
            throw new sfException('Wrong format for periode (' . $this->periode . ')');
        }
        if ($user = $this->getUser()) {
            if ($user->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
                $compte = $user->getCompte();
                $canInsertEditeur = true;
                if ($lastEditeur = $this->getLastEditeur()) {
                    $diff = Date::diff($lastEditeur->date_modification, date('c'), 'i');
                    if ($diff < 25) {
                        $canInsertEditeur = false;
                    }
                }
                if ($canInsertEditeur) {
                    $this->addEditeur($compte);
                }
            }
        }
        if ($this->isNew()) {
            $etablissement = $this->getEtablissement();
            $this->etablissement_num_interne = $etablissement->num_interne;
        }
    }

    public function isFictive()
    {
    	return false;
    }
    
	public function save() {
        parent::save();
        $this->updateBilan();
    }

    protected function getHistoriqueAbstract() {

        return DRMClient::getInstance()->getDRMHistorique($this->identifiant);
    }

    private function getTotalDroit($type) {
        $total = 0;
        foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
                $total += $appellation->get('total_' . $type);
            }
        }

        return $total;
    }

    private function interpretHash($hash) {
        if (!preg_match('|declaration/certifications/([^/]*)/appellations/([^/]*)/|', $hash, $match)) {

            throw new sfException($hash . " invalid");
        }

        return array('certification' => $match[1], 'appellation' => $match[2]);
    }

    private function setDroit($type, $appellation) {
        throw new sfException('Utilisé  ?');
        exit;
        $configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->periode);
        $droit = $appellation->droits->get($type);
        $droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
        $droit->libelle = $configurationDroits->libelle;
    }

    public function isPaiementAnnualise() {

        return $this->declaratif->paiement->douane->isAnnuelle();
    }

    public function getHumanDate() {
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

        return strftime('%B %Y', strtotime($this->periode . '-01'));
    }

    public function getEuValideDate() {

        return strftime('%d/%m/%Y', strtotime($this->valide->date_signee));
    }

    public function isDebutCampagne() {
        return DRMPaiement::isDebutCampagne(DRMClient::getInstance()->getMois($this->periode));
    }

    public function getCampagnePrecedente() {
        $annee = preg_replace('/([0-9]{4})-([0-9]{4})/', '$1', $this->campagne);
        return ($annee - 1) . '-' . $annee;
    }

    public function hasDaidsCampagnePrecedente() {
        $campagne = $this->getCampagnePrecedente();
        return (DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $campagne)) ? true : false;
    }

    public function hasDaids() {
        return (DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->campagne)) ? true : false;
    }

    public function getCurrentEtapeRouting() {
        $etape = sfConfig::get('app_drm_etapes_' . $this->etape);

        return $etape['url'];
    }

    public function setCurrentEtapeRouting($etape) {
        if (!$this->isValidee()) {
            $this->etape = $etape;
            $this->save();
        }
    }

    public function hasApurementPossible() {
        if (
                $this->declaratif->daa->debut ||
                $this->declaratif->daa->fin ||
                $this->declaratif->dsa->debut ||
                $this->declaratif->dsa->debut ||
                $this->declaratif->adhesion_emcs_gamma
        ) {

            return true;
        } else {

            return false;
        }
    }
    
    public function hasVolumeAcquittes()
    {
    	if (!$this->hasDroitsAcquittes()) {
    		return false;
    	}
    	foreach ($this->getDetails() as $detail) {
    		if ($detail->acq_total_debut_mois) {
    			return true;
    		}
    	}
    	return false;
    }

    public function setHasDroitsAcquittes($has = 0)
    {
    	$this->droits_acquittes = ($has)? 1 : 0;
    }
    
    public function hasDroitsAcquittes()
    {
    	return ($this->droits_acquittes)? true : false;
    }

    public function hasVrac() {
        $detailsVrac = $this->getDetailsAvecVrac();

        return (count($detailsVrac) > 0);
    }

    public function hasConditionneExport() {

        return ($this->declaration->getTotalByKey('sorties/export') > 0);
    }

    public function hasMouvementAuCoursDuMois() {

        return $this->hasVrac() || $this->hasConditionneExport();
    }

    public function isEnvoyee() {
        if (!$this->exist('valide'))
            return false;
        if (!$this->valide->exist('status'))
            return false;
        if ($this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_ENVOYEE && $this->valide->status != DRMClient::VALIDE_STATUS_VALIDEE_RECUE) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * Pour les users administrateur
     */

    public function canSetStockDebutMois($acq = false) {
        $isAdministrateur = ($this->getUser()) ? $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN) : false;
        if ($this->isDebutCampagne() || ($isAdministrateur && $this->hasVersion())) {
            return true;
        } else {
        	if ($acq) {
        		$mother = $this->getPrecedente();
        		if ($mother && $this->hasDroitsAcquittes() && !$mother->hasDroitsAcquittes()) {
        			return true;
        		}
        	}
            return false;
        }
    }

    public function hasProduits() {
        return (count($this->declaration->getProduits()) > 0) ? true : false;
    }

    public function hasEditeurs() {
        return (count($this->editeurs) > 0);
    }

    public function getLastEditeur() {
        if ($this->hasEditeurs()) {
            $editeurs = $this->editeurs->toArray();
            return array_pop($editeurs);
        } else {
            return null;
        }
    }

    public function getUser() {
        try {
            return sfContext::getInstance()->getUser();
        } catch (sfException $e) {
            return null;
        }
        return null;
    }

    public function addEditeur($compte) {
        $editeur = $this->editeurs->add();
        $editeur->compte = $compte->_id;
        $editeur->nom = $compte->nom;
        $editeur->prenom = $compte->prenom;
        $editeur->date_modification = date('c');
    }

    public function isRectificativeEnCascade() {
        if (!$this->isRectificative()) {
            return false;
        }
        $mother = $this->getMother();
        if ($mother && $mother->getPrecedente() && $this->getPrecedente()) {
            return ($mother->getPrecedente()->_id != $this->getPrecedente()->_id) ? true : false;
        }
        return false;
    }

    public function isSupprimable() {

        return !$this->isValidee() && !$this->isRectificativeEnCascade();
    }

    public function isSupprimableOperateur() {

        return !$this->isEnvoyee() && !$this->isRectificativeEnCascade();
    }

    public function validation($options = null) {

        return new DRMValidation($this, $options);
    }

    public function isIncomplete() {
        $incomplete = false;
        if ($this->exist('manquants')) {
            if ($this->manquants->igp || $this->manquants->contrats) {
                $incomplete = true;
            }
        }
        return $incomplete;
    }

    public function isIgpManquant() {
        if (!$this->isValidee()) {
            return false;
        }
        if (!$this->isIncomplete()) {
            return false;
        }
        return ($this->manquants->igp) ? true : false;
    }

    public function isContratManquant() {
        if (!$this->isValidee()) {
            return false;
        }
        if (!$this->isIncomplete()) {
            return false;
        }
        return ($this->manquants->contrats) ? true : false;
    }
    
    public function hasVolumeVracWithoutDetailVrac(){
    	$result = false;
        foreach ($this->getDetailsAvecVrac() as $detail) {
        	$totalVolume = 0;
			 foreach ($detail->vrac as $contrat) {
			 	$totalVolume += $contrat->volume;
			 }
			 if ($detail->canHaveVrac() && $detail->sorties->vrac) {
			  	  $ecart = round($detail->sorties->vrac * DRMValidation::ECART_VRAC, 4);
				  if (round($totalVolume,4) < (round($detail->sorties->vrac,4) - $ecart)) {
				    $result = true;
				    break;
				  }
			  }
        }
        return $result;
    }

    public function getLibelleBilan() {
            return DRMClient::getLibellesForStatusBilan($this->getStatutBilan());        
    }
    
    public function getStatutBilan() {
        if($this->isNew()){
            return DRMClient::DRM_STATUS_BILAN_A_SAISIR;
        }
        if(!$this->isValidee()){
            return DRMClient::DRM_STATUS_BILAN_NON_VALIDE;
        }
        if($this->isContratManquant() && $this->isIgpManquant()){
            return DRMClient::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT;
        }
        if($this->isContratManquant()){
            return DRMClient::DRM_STATUS_BILAN_CONTRAT_MANQUANT;
        }
        if($this->isIgpManquant()){
            return DRMClient::DRM_STATUS_BILAN_IGP_MANQUANT;
        }
        return DRMClient::DRM_STATUS_BILAN_VALIDE;
    }

    public function addObservationProduit($hash, $observation)
    {
    	if ($this->exist($hash)) {
    		$produit = $this->get($hash);
    		$produit->observations = $observation;
    	}
    }
    
    /*     * ** VERSION *** */

    public static function buildVersion($rectificative, $modificative) {

        return VersionDocument::buildVersion($rectificative, $modificative);
    }

    public static function buildRectificative($version) {

        return VersionDocument::buildRectificative($version);
    }

    public static function buildModificative($version) {

        return VersionDocument::buildModificative($version);
    }

    public function getVersion() {

        return $this->_get('version');
    }

    public function hasVersion() {

        return $this->version_document->hasVersion();
    }

    public function isVersionnable() {
        if (!$this->isValidee()) {

            return false;
        }

        return $this->version_document->isVersionnable();
    }

    public function getRectificative() {

        return $this->version_document->getRectificative();
    }

    public function isRectificative() {

        return $this->version_document->isRectificative();
    }

    public function isRectifiable() {

        return $this->version_document->isRectifiable();
    }

    public function getModificative() {

        return $this->version_document->getModificative();
    }

    public function isModificative() {

        return $this->version_document->isModificative();
    }

    public function isModifiable() {

        return $this->version_document->isModifiable();
    }

    public function isRectificativeAndModificative() {
        return $this->isModificative() && $this->isRectificative();
    }

    public function getPreviousVersion() {
    	return $this->version_document->getPreviousVersion();
    }

    public function getMasterVersionOfRectificative() {
        return DRMClient::getInstance()->getMasterVersionOfRectificative($this->identifiant, $this->periode, $this->getRectificative() - 1);
    }

    public function needNextVersion() {

        return $this->version_document->needNextVersion();
    }

    public function getMaster() {

        return $this->version_document->getMaster();
    }

    public function isMaster() {

        return $this->version_document->isMaster();
    }

    public function findMaster() {

        return DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $this->periode);
    }

    public function findDocumentByVersion($version) {
        return DRMClient::getInstance()->find(DRMClient::getInstance()->buildId($this->identifiant, $this->periode, $version));
    }

    public function getMother() {

        return $this->version_document->getMother();
    }

    public function motherGet($hash) {

        return $this->version_document->motherGet($hash);
    }

    public function motherExist($hash) {
        return ($this->getMother())? $this->version_document->motherExist($hash) : false;
    }

    public function motherHasChanged() {

        if (count($this->getDetails()) != count($this->getMother()->getDetails())) {

            return true;
        }
        
        if ($this->declaratif->paiement->douane->frequence != $this->getMother()->declaratif->paiement->douane->frequence) {
        	return true;
        }
        
        $change = false;
        foreach ($this->getDetails() as $detail) {
        	if ($old = $this->getMother()->get($detail->getHash())) {
        		if ($detail->total != $old->total) {
        			$change = true;
        			break;
        		}
        	} else {
        		$change = true;
        		break;
        	}
        }
        
        

        return $change;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative() {
        $drm = $this->version_document->generateRectificative();
        //$drm->updateVracVersion();
        //$drm->updateProduitsDiponibles();
        $drm->identifiant_drm_historique = null;
        return $drm;
    }

    public function generateModificative() {
        $drm = $this->version_document->generateModificative();
        //$drm->updateVracVersion();
        //$drm->updateProduitsDiponibles();
        $drm->identifiant_drm_historique = null;
        return $drm;
    }

    public function generateNextVersion() {
        if (!$this->hasVersion()) {
            $next = $this->version_document->generateModificativeSuivante();
        } else {
            $next = $this->version_document->generateNextVersion();
        }
        if (!$next) {
        	return null;
        }
        $next->identifiant_drm_historique = null;
        return $next;
    }

    public function listenerGenerateVersion($document) {
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        $this->replicate($document);
        $document->update();
    }

    protected function replicate($drm) {
        foreach ($this->getDiffWithMother() as $key => $value) {
            $this->replicateDetail($drm, $key, $value, 'acq_total', 'acq_total_debut_mois');
            $this->replicateDetail($drm, $key, $value, 'total', 'total_debut_mois');
            $this->replicateDetail($drm, $key, $value, 'total_interpro', 'total_debut_mois_interpro');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/bloque', 'stocks_debut/bloque');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/warrante', 'stocks_debut/warrante');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/instance', 'stocks_debut/instance');
            $this->replicateDetail($drm, $key, $value, 'stocks_fin/commercialisable', 'stocks_debut/commercialisable');
        }
    }

    protected function replicateDetail(&$drm, $key, $value, $hash_match, $hash_replication) {
        if (preg_match('|^(/declaration/certifications/.+/appellations/.+/mentions/.+/lieux/.+/couleurs/.+/cepages/.+/details/.+)/' . $hash_match . '$|', $key, $match)) {
            $detail = $this->get($match[1]);
            if (!$drm->exist($detail->getHash())) {
                $drm->addProduit($detail->getCepage()->getHash(), $detail->labels->toArray());
            }
            $drm->get($detail->getHash())->set($hash_replication, $value);
        }
    }

    public function getFormattedDateFromPeriode() {
        return preg_replace('/^([0-9]{4})-([0-9]{2})$/', '$1-$2-01', $this->periode);
    }

    /*     * ** FIN DE VERSION *** */

    /*     * ** MOUVEMENTS *** */

    public function getMouvements() {

        return $this->_get('mouvements');
    }

    public function getMouvementsCalcule() {  
        return $this->declaration->getMouvements();
    }

    public function getMouvementsCalculeByIdentifiant($identifiant) {

        return $this->mouvement_document->getMouvementsCalculeByIdentifiant($identifiant);
    }

    public function generateMouvements() {

        return $this->mouvement_document->generateMouvements();
    }

    public function findMouvement($cle, $id = null) {
        return $this->mouvement_document->findMouvement($cle, $id);
    }

    public function facturerMouvements() {

        return $this->mouvement_document->facturerMouvements();
    }

    public function isFactures() {

        return $this->mouvement_document->isFactures();
    }

    public function isNonFactures() {

        return $this->mouvement_document->isNonFactures();
    }

    public function clearMouvements() {
        $this->remove('mouvements');
        $this->add('mouvements');
    }

    /*     * ** FIN DES MOUVEMENTS *** */
    
    
    /* CREATION BILAN */
    
    public function updateBilan() {
       $bilan = BilanClient::getInstance()->findOrCreateByIdentifiant($this->identifiant, 'DRM');
       $bilan->updateEtablissement();
       $bilan->updateFromDRM($this);
       $bilan->save();
    }
    
    
    /* FIN DES MOUVEMENTS *** */
    
    /* EXPORTABLE */
    public function getExportableProduits() {
    	return $this->getDetails();
    }
    
    public function getExportableSucre() {
    	return array();
    }
    
    public function getExportableVracs() {
    	$details = $this->getDetailsAvecVrac();
    	$result = array();
    	foreach ($details as $detail) {
    		$result[$detail->getHash()] = array();
    		foreach ($detail->vrac as $contrat => $data) {
    			$result[$detail->getHash()][$contrat] = array(
    				DRMCsvEdi::CSV_CONTRAT_CONTRATID => $contrat,
    				DRMCsvEdi::CSV_CONTRAT_VOLUME => $data->volume,
    			);
    		}
    	}
    	return $result;
    }
    
    public function getExportableCrds() {
    	$crds = array();
    	foreach ($this->crds as $key => $crd) {
    		$crds[$key] = array();
    		$champs = array(
    			array('total_debut_mois', null),	
    			array('entrees', 'achats'),	
    			array('entrees', 'excedents'),	
    			array('entrees', 'retours'),	
    			array('sorties', 'utilisees'),	
    			array('sorties', 'detruites'),	
    			array('sorties', 'manquantes'),
    			array('total_fin_mois', null)
    		);
    		foreach ($champs as $index => $datas) {
    			if ($ligne = $this->getExportableCrd($crd, $datas[0], $datas[1])) {
    				$crds[$key][$index] = $ligne;
    			}
    		}
    	}
    	return $crds;
    }
    
    protected function getExportableCrd($crd, $cat, $type) {
    	$val = ($type)? $crd->get($cat)->get($type) : $crd->get($cat);
    	if ($val) {
    		return array(
    				DRMCsvEdi::CSV_CRD_GENRE => $crd->type->code,
    				DRMCsvEdi::CSV_CRD_COULEUR => $crd->categorie->code,
    				DRMCsvEdi::CSV_CRD_CENTILITRAGE => $crd->centilisation->code,
    				DRMCsvEdi::CSV_CRD_LIBELLE => $crd->libelle,
    				DRMCsvEdi::CSV_CRD_CATEGORIE_KEY => $cat,
    				DRMCsvEdi::CSV_CRD_TYPE_KEY => $type,
    				DRMCsvEdi::CSV_CRD_QUANTITE => $val,
    		);
    	}
    	return null;
    }
    
    public function getExportableAnnexes() {
    	return array();
    }
    
	public function hasExportableProduitsAcquittes() {
		return ($this->exist('droits_acquittes') && $this->droits_acquittes)? true : false;
	}

	public function getExportableObservations() {
		return 'observations';
	}

	public function getExportableStatistiquesEuropeennes() {
		$result = array();
		if ($this->declaratif->exist('statistiques')) {
			foreach ($this->declaratif->statistiques as $key => $val) {
				if ($val) {
					$result[$key] = $val;
				}
			}
		}
		return $result;
	}
	public function getExportableRna() {
		$result = array();
		if ($this->declaratif->exist('rna')) {
			foreach ($this->declaratif->rna as $rna) {
				$result[] = array(
					DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTDATEEMISSION => $rna->date,
					DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST => $rna->accises,
					DRMCsvEdi::CSV_ANNEXE_NUMERODOCUMENT => $rna->numero,
				);
			}
		}
		return $result;
	}
	public function getExportableDocuments() {
		$result = array();
		$champs = array('empreinte', 'daa', 'dsa');
		foreach ($champs as $champ) {
			if ($this->declaratif->exist($champ)) {
				$result[$champ] = array();
				if ($this->declaratif->get($champ)->debut) {
					$result[$champ][] = array(
							DRMCsvEdi::CSV_ANNEXE_TYPEMVT => 'debut',
							DRMCsvEdi::CSV_ANNEXE_QUANTITE => $this->declaratif->get($champ)->debut,
					);
				}
				if ($this->declaratif->get($champ)->fin) {
					$result[$champ][] = array(
							DRMCsvEdi::CSV_ANNEXE_TYPEMVT => 'fin',
							DRMCsvEdi::CSV_ANNEXE_QUANTITE => $this->declaratif->get($champ)->fin,
					);
				}
			}
		}
		return $result;
	}
	
	public function getExportableDeclarantInformations() {
		$result = array();
		$result[DRMCsvEdi::CSV_PERIODE] = $this->periode;
		$result[DRMCsvEdi::CSV_IDENTIFIANT] = $this->identifiant;
		$result[DRMCsvEdi::CSV_NUMACCISE] = $this->declarant->no_accises;
		return $result;
	}
	
	public function getExportableMvtDetails($key, $detail) {
		if ($key == 'crd_details') {
			$periode = sprintf('%4d-%02d', $detail->annee, $detail->mois);
			return array(
					DRMCsvEdi::CSV_CAVE_EXPORTPAYS => str_replace("-", "", $periode)
			);
		}
		return array();
	}
	
	public function setImportableMvtDetails($type, $categorie, $datas) {
		if ($type == 'crd' && $categorie->getKey() == 'entrees') {
			$details = $categorie->getOrAdd('crd_details');
			$detail = $details->getOrAdd(ConfigurationProduit::DEFAULT_KEY);
			if (preg_match('/^([0-9]{4})([0-9]{2})$/', $datas[DRMCsvEdi::CSV_CAVE_EXPORTPAYS], $m)) {
				$detail->annee = $m[1];
				$detail->mois = $m[2];
				$detail->volume = $categorie->get($type);				
				return true;
			}
			return false;
		}
		return true;
	}
	
	public function getExportableCategoriesMouvements() {
		return array('total_debut_mois', 'acq_total_debut_mois', 'stocks_debut', 'entrees', 'sorties', 'stocks_fin', 'total', 'acq_total', 'tav', 'premix', 'observations');
	}
	
	public function getExportableCategorieByType($type) {
		if (in_array($type, array('tav', 'premix', 'observations', 'retiraison'))) {
			return 'complement';
		}
		if (in_array($type, array('total_debut_mois', 'acq_total_debut_mois', 'total', 'acq_total'))) {
			return 'stocks';
		}
		return null;
	}
	
	public function getExportableLibelleMvt($key) {
		return str_replace(array('acq_', '_details'), '', $key);
	}
	
	public function getImportableLibelleMvt($type, $key) {
		if ($type == DRMCsvEdi::TYPE_DROITS_ACQUITTES) {
			return 'acq_'.$key;
		}
		return $key;
	}
	
	public function getExportableCountryList() {
		return array();
	}

	public function getTotalStockAcq() {
		$produits = $this->getExportableProduits();
		$stock = 0;
		foreach ($produits as $produit) {
			$stock += $produit->acq_total;
		}
		return $stock;
	
	}
	public function getTotalStock() {
		$produits = $this->getExportableProduits();
		$stock = 0;
		foreach ($produits as $produit) {
			$stock += $produit->total;
		}
		return $stock;
	}
	
	public function setImportableSucre($quantite) {
		return;
	}
	
	public function getImportableDeclaratif() {
		return $this->declaratif;
	}
	
	public function setImportableRna($numero, $accises, $date) {
		$rna = $this->declaratif->rna->getOrAdd($numero);
		$rna->numero = $numero;
		$rna->accises = $accises;
		$rna->date = $date;
	}
	
	public function setImportablePeriode($periode) {
		$this->periode = substr($periode, 0, 4).'-'.substr($periode, -2);
	}
	
	public function setImportableIdentifiant($identifiant) {
		$this->identifiant = $identifiant;
		$this->setEtablissementInformations();
	}
	
	public function getDefaultKeyNode() {
		return ConfigurationProduit::DEFAULT_KEY;
	}
    /* FIN EXPORTABLE */
}
