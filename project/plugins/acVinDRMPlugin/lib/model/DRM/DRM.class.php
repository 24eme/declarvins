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
    protected static $mvtsSurveilles = array(
        'Récolte / revendication' => 'entrees/recolte',
        'Entrée replacement en suspension CRD' => 'entrees/crd',
        'Sortie mvt. temporaire : Transfert de chai' => 'sorties/mouvement',
        'Sortie autres' => 'sorties/pertes',
        'Mvt. temporaire : Embouteillage' => 'sorties/embouteillage',
        'Mvt. temporaire : Travail à façon' => 'sorties/travail'
    );
    protected static $mvtsSurveillesHashConstraint = array(
        'entrees/recolte' => ["appellations/CVG/mentions/DEFAUT/lieux/LAU"]
    );
    protected static $appellationsPopupAdelphe = ['CDR', 'CVS', 'CVG', 'BEA', 'CDP', 'CGR', 'COD', 'COR', 'CRO', 'CRH', 'GIG', 'HER', 'LIR', 'RTA', 'SJO', 'SPT', 'TAV', 'VAC', 'VBR', 'CAR', 'GRI', 'LAU', 'SPE', 'SPB'];

    public function __construct() {
        parent::__construct();
        $this->initDocuments();
        $config_certifications = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->getCertifications();
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

    public function getDateDebutPeriode() {
        if (!$this->periode) {
            return null;
        }
        return DRMClient::getInstance()->buildDateDebut($this->periode);
    }

    public function setPeriode($periode) {
        $this->campagne = DRMClient::getInstance()->buildCampagne($periode);

        return $this->_set('periode', $periode);
    }

    public function getProduit($hash, $labels = array(), $complement_libelle = null) {
        if (!$this->exist($hash)) {
            return false;
        }

        return $this->get($hash)->details->getProduit($labels, $complement_libelle);
    }

    public function isNegoce() {
        if (!$this->declarant->famille) {
            $this->setEtablissementInformations();
        }
        return $this->declarant->famille == EtablissementFamilles::FAMILLE_NEGOCIANT && $this->declarant->sous_famille != EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR;
    }

    public function isProducteur() {
        if (!$this->declarant->famille) {
            $this->setEtablissementInformations();
        }
        return $this->declarant->famille == EtablissementFamilles::FAMILLE_PRODUCTEUR;
    }

    public function getCielLot() {
        return ($this->isNegoce())? 'lot1' : 'lot2';
    }

    public function payerReport()
    {
    	$this->declaratif->paiement->douane->report_paye = 1;
    }

    public function addProduit($hash, $labels = array(), $complement_libelle = null) {
        if (!$labels) {
            $labels = array();
        }
    	if (!is_array($labels)) {
    		$labels = array($labels);
    	}
        if ($p = $this->getProduit($hash, $labels, $complement_libelle)) {
            return $p;
        }
        $detail = $this->getOrAdd($hash)->details->addProduit($labels, $complement_libelle);
        $detail->updateVolumeBloque();
        return $detail;
    }

    public function transferToCiel() {
        $drmCiel = $this->getOrAdd('ciel');
        $export = new DRMExportCsvEdi($this);
        $etablissement = $this->getEtablissement();
        if ($xml = $export->exportEDI('xml')) {
            $transfert = true;
            try {
                $service = new CielService($etablissement->interpro);
                $drmCiel->xml = $service->transfer($xml);
                $drmCiel->setInformationsFromXml();
                $transfert = $drmCiel->isTransfere();
            } catch (sfException $e) {
                $transfert = false;
            }
        }
        if ($transfert) {
            $this->validate();
        }
        $this->save();
        return $transfert;
    }

    public function restoreLibelle()
    {
    	if ($drmPrecedente = $this->getPrecedente()) {
    		foreach ($this->getDetails() as $detail) {
    			if ($drmPrecedente->exist($detail->getHash())) {
    				$detailPrecedent = $drmPrecedente->get($detail->getHash());
    				$detail->libelle = $detailPrecedent->libelle;
    			}
    		}
    	}
    }

    public function addCrd($categorie, $type, $centilisation, $centilitre, $bib, $stock = 0)
    {
    	$idCrd = DRMCrd::makeId($categorie, $type, $centilisation, $centilitre, $bib);
    	$crd = $this->crds->getOrAdd($idCrd);
    	if (!$crd->libelle) {
    		$crd->addCrd($categorie, $type, $centilisation, $centilitre, $bib, $stock);
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

    public function getDetails($interpro = null) {

        return $this->declaration->getProduits($interpro);
    }

    public function getProduitsCepages() {

        return $this->declaration->getProduitsCepages();
    }

    public function getProduits() {
        return $this->declaration->getProduits();
    }

    public function getDetailsAvecVrac() {
        $details = array();
        foreach ($this->getDetails() as $d) {
            if (($d->getTotalVrac() && $d->canHaveVrac()) || ($d->hasVracs())) {
                $details[] = $d;
            }
        }

        return $details;
    }

    public function getProduitByIdDouane($hash, $idDouane, $labels = null, $complement_libelle = null)
    {
        if ($labels && !is_array($labels)) {
            $labels = array($labels);
        }else{
            $labels = array();
        }
        if ($p = $this->getProduit($hash, $labels, $complement_libelle)) {
            if (trim($p->getIdentifiantDouane()) == trim($idDouane)) {
                return $p;
            }
        }
        if (!$labels) {
            if ($p = $this->getProduit($hash, array(), $complement_libelle)) {
                if (trim($p->getIdentifiantDouane()) == trim($idDouane)) {
                    return $p;
                }
            }
        }
        if (!$labels && !$complement_libelle) {
            $idcomplement = 'DEFAUT';
        }else{
            $idcomplement = DRMDetails::hashifyLabels($labels, $complement_libelle);
        }
        foreach ($this->getDetails() as $detail) {
            if (trim($detail->getIdentifiantDouane()) == trim($idDouane) && strtoupper($idcomplement) == strtoupper($detail->getKey())) {
                return $detail;
            }
            if (trim($detail->getIdentifiantDouane()) == trim($idDouane) && KeyInflector::slugify($complement_libelle) == KeyInflector::slugify($detail->getLibelle())) {
                return $detail;
            }
        }
        return null;
    }

    public function getProduitsByIdDouaneAndStockDebut($idDouane, $libelle, $label, $stockDebut) {
        $inaoLibelleProduits = [];
        $libelleProduits = [];
        $inaoProduits = [];
        foreach ($this->getDetails() as $detail) {
            if ($label && !in_array($label, $detail->labels->toArray(true,false))) {
                continue;
            }
            if (trim($libelle) == trim($detail->libelle) && trim($detail->getIdentifiantDouane()) == trim($idDouane) && round($stockDebut,5) == round($detail->get('total_debut_mois'), 5)) {
                $inaoLibelleProduits[] = $detail;
            }
            if ($libelle && trim($libelle) == trim($detail->libelle) && round($stockDebut,5) == round($detail->get('total_debut_mois'), 5)) {
                $libelleProduits[] = $detail;
            }
            if (!$libelle && trim($detail->getIdentifiantDouane()) == trim($idDouane) && round($stockDebut,5) == round($detail->get('total_debut_mois'), 5)) {
                $inaoProduits[] = $detail;
            }
        }
        if ($inaoLibelleProduits) {
            return $inaoLibelleProduits;
        } elseif ($libelleProduits) {
            return $libelleProduits;
        }
        return $inaoProduits;
    }

    public function getDetailsVracSansContrat() {
        $details = array();
        foreach ($this->getDetailsAvecVrac() as $detail) {
        	$totalVolume = 0;
        	foreach ($detail->vrac as $contrat) {
        		$totalVolume += $contrat->volume;
        	}
        	if ($detail->canHaveVrac() && $detail->getTotalVrac()) {
        		$ecart = round($detail->getTotalVrac() * DRMValidation::ECART_VRAC,5);
        		if (round($totalVolume,5) < (round($detail->getTotalVrac(),5) - $ecart)) {
        			$details[] = $detail;
        		}
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
        if ($produits = $this->getProduitsReserveInterpro()) {
            foreach ($produits as $produit) {
                $produit->updateSuiviSortiesChais();
                $produit->updateAutoReserveInterpro();
            }
        }
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
            $this->declaratif->paiement->cvo->frequence = null;
            $this->declaratif->paiement->cvo->moyen = null;
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
        $this->declaratif->paiement->douane->report_paye = null;
        $this->declaratif->paiement->cvo->report_paye = null;
        $this->declaratif->remove('reports');
        $this->declaratif->add('reports');

        $this->commentaires = null;

        if ($this->exist('ciel')) {
        	$this->remove('ciel');
        	$this->add('ciel');
        }


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
                $tav = (!$detail->isVin())? $detail->tav : null;
                $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE)->getOrAdd($droitDouane->code)->integreVolume($detail->sommeLignes(DRMDroits::getDouaneDroitSorties()), $detail->sommeLignes(DRMDroits::getDouaneDroitEntrees()), $droitDouane->taux, 0, $droitDouane->libelle, $tav);
                $codeTotal = DRMDroitsCirculation::getCorrespondanceCode($droitDouane->code).'_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL;
                $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE)->getOrAdd($codeTotal)->integreVolume($detail->sommeLignes(DRMDroits::getDouaneDroitSorties()), $detail->sommeLignes(DRMDroits::getDouaneDroitEntrees()), $droitDouane->taux, 0, $codeTotal, $tav);
            }
        }
        $douanes = $this->droits->getOrAdd(DRMDroits::DROIT_DOUANE);
        foreach ($douanes as $k => $douane) {
        	$round = (preg_match('/\_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL.'/', $k))? 0 : 2;
        	$douane->total = round($douane->total, $round);
        	if ($report = $this->getReportByDroit(DRMDroits::DROIT_DOUANE, $douane->code)) {
        		$douane->report = round($report, $round);
        		$douane->cumul = round($report + $douane->total, $round);
        	}
        }
    }

    public function hasStocksEpuise() {
        $hasStock = false;
        foreach ($this->getDetails() as $detail) {
            if ($detail->total > 0 || $detail->acq_total > 0) {
                $hasStock = true;
                break;
            }
        }
        return !$hasStock;
    }

    public function isNeant() {
    	$hasStockAcq = $this->hasStocksAcq();
    	$hasStockSus = $this->hasStocks();

    	return ($hasStockSus || $hasStockAcq)? false : true;
    }

    public function hasStocks() {
    	$details = $this->getDetails();
    	$hasStock = false;
    	foreach ($details as $detail) {
    		if ($detail->total_debut_mois > 0 || $detail->total > 0 || $detail->total_entrees > 0 || $detail->total_sorties > 0) {
    			$hasStock = true;
    			break;
    		}
    	}
    	return $hasStock;
    }

    public function hasStocksAcq() {
    	$details = $this->getDetails();
    	$hasStock = false;
    	foreach ($details as $detail) {
    		if ($detail->acq_total_debut_mois || $detail->acq_total > 0 || $detail->acq_total_entrees > 0 || $detail->acq_total_sorties > 0) {
    			$hasStock = true;
    			break;
    		}
    	}
    	return $hasStock;
    }

    public function hasMouvements($hash) {
    	$details = $this->getDetails();
    	foreach ($details as $detail) {
    		if ($detail->exist($hash) && $detail->get($hash) > 0) {
    			return true;
    		}
    	}
    	return false;
    }
	public function isNouvelleCampagne() {
		if ($this->getMois() == $this->getEtablissement()->getMoisToSetStock()) {
			return true;
		}
		return false;
	}
    public function getReportByDroit($type, $droit) {
    	$reportSet = 0;
    	if (preg_match('/\_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL.'/', $droit)) {
    		$reports = $this->declaratif->getOrAdd('reports');
    		if ($reports->exist($droit)) {
    			$reportSet = $reports->get($droit);
    		}
    	}
    	if ($this->declaratif->paiement->get($type)->exist('report_paye') && $this->declaratif->paiement->get($type)->get('report_paye')) {
    		return 0;
    	}
        $drmPrecedente = $this->getPrecedente();
        if ($drmPrecedente && !$drmPrecedente->isNew()) {
            if ($drmPrecedente->droits->get($type)->exist($droit)) {
                return ($drmPrecedente->droits->get($type)->get($droit)->cumul + $reportSet);
            }
        }
        return $reportSet;
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

    public function isFirstCiel() {
    	if (!$this->getEtablissementObject()->isTransmissionCiel()) {
    		return false;
    	}
    	$precedente = $this->getPrecedente();
    	$precedenteCiel = $precedente->getOrAdd('ciel');
    	return !$precedenteCiel->isTransfere();
    }

    public function getPrecedente($always_precedente = false) {
    	$periode = DRMClient::getInstance()->getPeriodePrecedente($this->periode);
    	$campagne = DRMClient::getInstance()->buildCampagne($periode);
        if (!$always_precedente && $this->isMoisOuvert()) {
    		return new DRM();
    	}
    	if ($precente = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $periode)) {
    		return $precente;
    	}
        if ($always_precedente) {
            return null;
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
    	  if ($this->hasVersion()) {
        	if ($previous = $this->getMother()) {
            	$previous->referente = 1;
              $previous->save();
            }
        }
        $this->annuleUpdateVrac();
    }

    public function cleanCiel() {
    	if ($this->exist('ciel')) {
    		$this->remove('ciel');
    		$this->add('ciel');
    	}
    }

    public function annuleUpdateVrac()
    {
    	$mothers = array();
    	foreach ($this->getDetails() as $detail) {
    		foreach ($detail->vrac as $numero => $vrac) {
    			$volume = $vrac->volume;
    			$contrat = VracClient::getInstance()->findByNumContrat($numero);
                if (!$contrat) {
                    continue;
                }
    			$contrat->soustraitVolumeEnleve($volume);
    			$enlevements = $contrat->getOrAdd('enlevements');
    			if ($this->hasVersion()) {
    				if ($previous = $this->getMother()) {
    					$mothers[] = $previous;
	    				if ($contrat->enlevements->exist($previous->_id)) {
	    					$contrat->enlevements->remove($previous->_id);
	    				}
    				}
    			}
    			if ($contrat->enlevements->exist($this->_id)) {
    				$contrat->enlevements->remove($this->_id);
    			}
    			$contrat->save();
    		}
    	}
    }

    public function isValidee() {

        return ($this->valide->date_saisie);
    }

    public function validateAutoCiel($xml) {
        $this->ciel->transfere = 1;
        $this->ciel->valide = 1;
        $this->ciel->identifiant_declaration = "AUTOGENERE";
        $this->ciel->horodatage_depot = date('c');
        $this->ciel->xml = $xml;

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
    	return $this->isModeDeSaisie(DRMClient::MODE_DE_SAISIE_DTI);
    }

    public function isModeDeSaisie($modeDeSaisie) {
        return ($this->mode_de_saisie == $modeDeSaisie);
    }

    public function getDtiPlusCSV() {
        if ($doc = CSVClient::getInstance()->find('CSV-'.$this->_id)) {
            return $doc;
        }
        return false;
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
                if ($contrat = VracClient::getInstance()->findByNumContrat($numero)) {
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
    }

    public function updateCrds() {
    	foreach ($this->crds as $crd) {
    		$crd->updateStocks();
    	}
    }

    public function hasMouvementsCrd() {
    	$mvt = false;
    	foreach ($this->crds as $crd) {
    		foreach ($crd->entrees as $entree => $vol) {
    			if ($vol) {
    				$mvt = true;
    			}
    		}
    		foreach ($crd->sorties as $sortie => $vol) {
    			if ($vol) {
    				$mvt = true;
    			}
    		}
    	}
    	return $mvt;
    }

    public function updateVracVersion() {
        foreach ($this->getDetails() as $detail) {
            foreach ($detail->vrac as $numero => $vrac) {
                $volume = $vrac->volume;
                if ($contrat = VracClient::getInstance()->findByNumContrat($numero)) {
                    $contrat->soustraitVolumeEnleve($volume);
                    $enlevements = $contrat->getOrAdd('enlevements');
                    if ($enlevements->exist($this->_id)) {
                    	$enlevements->remove($this->_id);
                    }
                    $contrat->save();
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

    public function isMoisOuvert() {
        $mois = ($this->getEtablissementObject())? $this->getEtablissementObject()->getMoisToSetStock() : DRMPaiement::NUM_MOIS_DEBUT_CAMPAGNE;
        return DRMClient::getInstance()->getMois($this->periode) == $mois;
    }

    public function getCampagnePrecedente() {
        $annee = preg_replace('/([0-9]{4})-([0-9]{4})/', '$1', $this->campagne);
        return ($annee - 1) . '-' . $annee;
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
                $this->declaratif->dsa->debut
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
        if ($this->isMoisOuvert() || ($isAdministrateur && $this->hasVersion())) {
            return true;
        } else {
        	$mother = $this->getPrecedente();
        	$mother = ($mother->isNew())? null : $mother;
        	if (!$mother) {
        		return true;
        	}
        	if ($acq) {
        		if ($mother && $this->hasDroitsAcquittes() && !$mother->hasDroitsAcquittes()) {
        			return true;
        		}
        	}
        	if ($mother && !$mother->ciel->transfere && $mother->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER && $this->mode_de_saisie != DRMClient::MODE_DE_SAISIE_PAPIER) {
        		return true;
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
			 if ($detail->canHaveVrac() && $detail->getTotalVrac()) {
			  	  $ecart = round($detail->getTotalVrac() * DRMValidation::ECART_VRAC,5);
				  if (round($totalVolume,5) < (round($detail->getTotalVrac(),5) - $ecart)) {
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
        $drmCiel = $this->getOrAdd('ciel');
        if ($drmCiel->isValide()) {
        	return DRMClient::DRM_STATUS_BILAN_VALIDE_CIEL;
        }
        if ($drmCiel->isTransfere() && $drmCiel->getReponseCiel()) {
        	return DRMClient::DRM_STATUS_BILAN_ENVOYEE_CIEL;
        }
        if ($this->isRectificative() && $drmCiel->isTransfere() && !$drmCiel->valide && !$drmCiel->getReponseCiel()) {
        	return DRMClient::DRM_STATUS_BILAN_DIFF_CIEL;
        }
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

    public function getObservationsProduit() {
        $obs = array();
        foreach ($this->getDetails() as $detail) {
            if ($detail->observations) {
                $obs[] = $detail;
            }
        }
        return $obs;
    }

    public function addObservationProduit($hash, $observation)
    {
    	if ($this->exist($hash)) {
    		$produit = $this->get($hash);
    		$produit->observations = "".str_replace(';', '', $observation);
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
        if ($this->exist('ciel') && $this->ciel->transfere) {
          return false;
        }
        return $this->version_document->isRectifiable();
    }

    public function getModificative() {
    	if ($this->exist('ciel') && $this->ciel->transfere) {
    		return false;
    	}
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

    public function hasRectifications() {
    	if(!$this->hasVersion()) {
    		return false;
    	}
    	$diffs = $this->getDiffWithMother();
    	$diff = false;
    	foreach ($diffs as $hash => $val) {
    		if (preg_match('/\/declaration\//', $hash)) {
    			$diff = true;
    			break;
    		}
    		if (preg_match('/\/crds\//', $hash)) {
    			$diff = true;
    			break;
    		}
    		if (preg_match('/\/declaratif\//', $hash)) {
    			$diff = true;
    			break;
    		}
    	}
    	return $diff;
    }

    public function getDiffWithMother() {

        return $this->version_document->getDiffWithMother();
    }

    public function isModifiedMother($hash_or_object, $key = null) {
        return $this->version_document->isModifiedMother($hash_or_object, $key);
    }

    public function generateRectificative($force = false) {
        $drm = $this->version_document->generateRectificative($force);
        //$drm->updateVracVersion();
        //$drm->updateProduitsDiponibles();
        $drm->identifiant_drm_historique = null;
        $drm->declaratif->paiement->douane->report_paye = null;
        return $drm;
    }

    public function generateModificative() {
        $drm = $this->version_document->generateModificative();
        //$drm->updateVracVersion();
        //$drm->updateProduitsDiponibles();
        $drm->identifiant_drm_historique = null;
        $drm->declaratif->paiement->douane->report_paye = null;
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
        $this->updateReplicate($document);
        $document->update();
        $document->devalide();
    }

    public function listenerGenerateNextVersion($document) {
        $this->replicate($document);
        $document->update();
    }

    protected function updateReplicate($drm) {
    	$precedente = $drm->getPrecedente();
    	if ($precedente && $drm->getCampagne() == $precedente->getCampagne()) {
    		foreach ($precedente->getDetails() as $detail) {
    			if ($drm->exist($detail->getHash())) {
    				$drm->get($detail->getHash())->set('total_debut_mois', $detail->get('total'));
    				$drm->get($detail->getHash())->set('total_debut_mois_interpro', $detail->get('total_interpro'));
    				$drm->get($detail->getHash())->set('stocks_debut/bloque', $detail->get('stocks_fin/bloque'));
    				$drm->get($detail->getHash())->set('stocks_debut/warrante', $detail->get('stocks_fin/warrante'));
    				$drm->get($detail->getHash())->set('stocks_debut/instance', $detail->get('stocks_fin/instance'));
    				$drm->get($detail->getHash())->set('stocks_debut/commercialisable', $detail->get('stocks_fin/commercialisable'));
    			}
    		}
    	}
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

    public function isFactures($interpro = null) {
        foreach($this->getMouvements() as $mouvements) {
            foreach($mouvements as $mouvement) {
                if($mouvement->facture) {
                    if (!$interpro||($interpro && $mouvement->interpro == $interpro)) {
                      return true;
                    }
                }
            }
        }
        return false;
    }

    public function isNonFactures($interpro = null) {
        return !$this->isFactures($interpro);
    }

    public function clearMouvements() {
        $this->remove('mouvements');
        $this->add('mouvements');
    }

    /*     * ** FIN DES MOUVEMENTS *** */


    /* FIN DES MOUVEMENTS *** */

    /* EXPORTABLE */
    public function getExportableProduits($interpro = null) {
    	return $this->getDetails($interpro);
    }

    public function getCielProduits() {
        $e = $this->getEtablissementObject();
        $produits = $this->getDetails();

        return $produits; // on ne s'occupe plus de la drm précédente car peut poser probleme

        if ($this->isMoisOuvert()) {
            return $produits;
        }
        $drm_precedente = $this->getPrecedente(true);
        if (!$drm_precedente) {
            return $produits;
        }

        $produits_fait = [];

        foreach ($produits as $detail) {
                $produits_fait[] = $detail->getCepage()->libelle_fiscal.$detail->getCepage()->inao;
        }

        $drm_precedente->init(['keepStock' => false]);
        foreach ($drm_precedente->getDetails() as $detail) {
            if (in_array($detail->getCepage()->libelle_fiscal.$detail->getCepage()->inao, $produits_fait)) {
                continue;
            }
            $produits_fait[] = $detail->getCepage()->libelle_fiscal.$detail->getCepage()->inao;
            $produits[] = $detail;
        }

        return $produits;
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
			foreach ($this->declaratif->rna as $rna) if ($rna->date) {
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
				if ($this->declaratif->get($champ)->nb) {
					$result[$champ][] = array(
							DRMCsvEdi::CSV_ANNEXE_TYPEMVT => 'nb',
							DRMCsvEdi::CSV_ANNEXE_QUANTITE => $this->declaratif->get($champ)->nb,
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
		if ($this->declarant->siret) {
			$result[DRMCsvEdi::CSV_IDENTIFIANT] .= ' ('.$this->declarant->siret.')';
		} elseif ($this->declarant->cvi) {
			$result[DRMCsvEdi::CSV_IDENTIFIANT] .= ' ('.$this->declarant->cvi.')';
		}
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
			$data = str_replace('-', '', $datas[DRMCsvEdi::CSV_CAVE_EXPORTPAYS]);
			if (preg_match('/^([0-9]{4})([0-9]{2})$/', $data, $m)) {
			    $detail = $details->getOrAdd($m[1].$m[2]);
				$detail->annee = $m[1];
				$detail->mois = $m[2];
				$detail->volume = $detail->volume + DRMImportCsvEdi::floatizeVal($datas[DRMCsvEdi::CSV_CAVE_VOLUME]);
				return true;
			}
			return false;
		}
		return true;
	}

	public function getExportableCategoriesMouvements() {
		return array('total_debut_mois', 'acq_total_debut_mois', 'entrees', 'sorties', 'total', 'acq_total', 'tav', 'premix', 'observations');
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

	public function getImportableLibelleMvt($type, $categorie, $key) {
		if ($type == DRMCsvEdi::TYPE_DROITS_ACQUITTES && $categorie != 'complement') {
			return 'acq_'.$this->getImportableMvt($key);
		}
		return $this->getImportableMvt($key);
	}

	private function getImportableMvt($key) {

        $key = str_replace("autre_", "autres_", $key);

        if(in_array($key, array("achats", "acq_achats", "acq_crds", "acq_replacements", "bloques", "commercialisables", "consommations", "crds", "declassements", "distillations", "embouteillages", "excedents", "exports", "instances", "manipulations", "mouvements", "mutages", "recoltes", "replis", "travails", "vcis", "vracs"," warrantes", "autres_internes"))) {

            return preg_replace('/s$/', '', $key);
        }

        if(in_array($key, array("facture","lie","perte","autre","acq_autre", "observation", "crd_acquitte"))) {

            return $key.'s';
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

	public function setImportableIdentifiant($identifiant = null, $ea = null, $siretCvi = null) {

		$referent = null;
		if ($identifiant) {
			$referent = EtablissementClient::getInstance()->find($identifiant);
		}
		if (!$referent) {
			$referent = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->identifyEtablissement($identifiant);
		}
		if (!$referent && $ea) {
			$referent = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->identifyEtablissement($ea);
		}
		if (!$referent && $siretCvi) {
			$referent = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->identifyEtablissement($siretCvi);
		}
		if (!$referent) {
			return false;
		}
		$this->identifiant = $referent->identifiant;
		$this->setEtablissementInformations();
		return true;
	}

	public function getDefaultKeyNode() {
		return ConfigurationProduit::DEFAULT_KEY;
	}
    /* FIN EXPORTABLE */
  public function hasIncitationDS() {
    $droit = ConfigurationClient::getCurrent()->isApplicationOuverte('INTERPRO-CIVP', 'ds') && $this->getEtablissement()->hasZone(ConfigurationZoneClient::ZONE_PROVENCE) && $this->getEtablissement()->hasDroit(EtablissementDroit::DROIT_DS) && preg_match('/^([0-9]{4})-07$/', $this->periode);
    $produits = false;
    foreach ($this->getDetails('INTERPRO-CIVP') as $detail) {
      if ($detail->getCouleur()->getKey() == 'rose' && $detail->cvo->taux) {
        $produits = true;
        break;
      }
    }
    return ($droit && $produits);
  }

  public function getVolumesSurveilles($interpro) {
      $volumes = array();
      foreach ($this->getDetails($interpro) as $detail) {
          foreach(self::$mvtsSurveilles as $mvtLibelle => $mvtHash) {
              if ($detail->get($mvtHash) > 0) {
                  if (isset(self::$mvtsSurveillesHashConstraint[$mvtHash])) {
                      $add = !empty(array_filter(self::$mvtsSurveillesHashConstraint[$mvtHash], fn($term) => strpos($detail->getHash(), $term) !== false));
                  } else {
                      $add = true;
                  }
                  if ($add) {
                      $volumes[$detail->getFormattedLibelle().' - '.$mvtLibelle] = $detail->get($mvtHash);
                  }
              }
          }
      }
      return $volumes;
  }

  public function getProduitsReserveInterpro($hash = null) {
      $produits = array();
      foreach($this->getProduitsCepages() as $p) {
          if ($hash && strpos($p->getHash(), $hash) === false) continue;
          if ($p->hasReserveInterpro()) {
              $produits[] = $p;
          }
      }
      return $produits;
  }

  public function hasUtilisationReserveInterpro() {
      foreach ($this->getProduitsReserveInterpro() as $produit) {
          if ($produit->getVolumeCommercialisable() < 0) return true;
      }
      return false;
  }

  public static function getFactureCalculeeParameters($interpro = null) {
      $filters_parameters = [];
      $filters_parameters['date_mouvement'] = date('Y-m-d');
      $filters_parameters['date_facturation'] = date('Y-m-d');
      $filters_parameters['message_communication'] = "";
      $filters_parameters['type_document'] = GenerationClient::TYPE_DOCUMENT_FACTURES;
      $filters_parameters['modele'] = "DRM";
      $filters_parameters['seuil'] = FactureConfiguration::getInstance($interpro)->getSeuilMinimum();
      $filters_parameters['interpro'] = $interpro;
      return $filters_parameters;
  }

  public function getFactureCalculee($interpro = null, $withSeuil = false, $onlyMvts = []) {
      $parameters = self::getFactureCalculeeParameters($interpro);
      if (!$withSeuil && isset($parameters['seuil'])) {
          unset($parameters['seuil']);
      }
      try {
          $etablissement = $this->getEtablissement();
      } catch (Exception $e) {
          return null;
      }
      $societe = $etablissement->getSociete();
      if (!$societe) {
          return null;
      }

      $mvtsCalcules = $this->getMvtsViewCalcules($interpro, $onlyMvts);

      if (!$mvtsCalcules) {
          return null;
      }

      $mvtsCalcules = MouvementfactureFacturationView::getInstance()->buildMouvements($mvtsCalcules);

      $mvts = FactureClient::getInstance()->filterWithParameters([$this->identifiant => $mvtsCalcules], $parameters);

      $mvts = $mvts[$this->identifiant];

      if(!$mvts || !count($mvts)) {
          return null;
      }

      $facture = FactureClient::getInstance()->createDocFromMouvements($mvts, $societe, $parameters['modele'], $parameters['date_facturation'], $parameters['message_communication']);
      return $facture;
  }

  public function getMvtsViewCalcules($interpro = null, $onlyMvts = []) {
      $items = [];
      $drmMvts = $this->getMouvements();
      if (isset($drmMvts[$this->identifiant])) {
          foreach($drmMvts[$this->identifiant] as $key => $mvt) {
              if (!$mvt->facturable) continue;
              if ($interpro && $interpro != $mvt->interpro) continue;
              if ($onlyMvts && !in_array($key, $onlyMvts)) continue;
              if (isset($items[$mvt->produit_libelle])) {
                  $mvtValue = $items[$mvt->produit_libelle]->value;
                  $mvtValue[2] += $mvt->volume*-1;
                  $mvtValue[7][] = $this->_id.':'.$key;
                  $items[$mvt->produit_libelle]->value = $mvtValue;
                  continue;
              }
              $item = new stdClass();
              $item->key = [$mvt->facture, $mvt->facturable, $mvt->interpro, $mvt->region, $this->identifiant, $this->type, $mvt->categorie, $mvt->produit_hash, $this->periode, $mvt->date, $mvt->vrac_numero, $mvt->vrac_destinataire, $mvt->type_hash, $mvt->detail_identifiant, $mvt->type_drm];
              $item->value = [$mvt->produit_libelle, $mvt->type_libelle, $mvt->volume*-1, $mvt->cvo, $mvt->vrac_destinataire, $mvt->detail_libelle, $this->_id, [$this->_id.':'.$key]];
              $items[$mvt->produit_libelle] = $item;
          }
      }
      return $items;
  }

  public function hasAppellationsAdelphe()
  {
      foreach ($this->getDetails() as $detail) {
          if (in_array($detail->getAppellation()->getKey(), self::$appellationsPopupAdelphe)) {
              return true;
          }
      }
      return false;
  }
}
