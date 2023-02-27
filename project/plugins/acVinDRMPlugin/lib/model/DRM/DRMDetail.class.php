<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

    protected $_config = null;
    const START_FACTURATION_MVT_AT = "2022-01-31";

    public function getConfig() {
        if (!$this->_config) {
            $this->_config = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->getConfigurationProduit($this->getCepage()->getHash());
        }
        return $this->_config;
    }
    public function getGeneratedLibelle() {
    	return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), array(), "%g% %a% %l% %co% %ce%");
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {
    	$libelle = ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), $this->labels->toArray(), "%g% %a% %l% %co% %ce%");
        if ($this->libelle != $libelle) {
    		return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), array_merge(array($this->libelle), $this->labels->toArray()), $format);
    	}
        return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), $this->labels->toArray(), $format);
    }

    public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") {
        return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getCodes(), array(), $format);
    }

    public function makeFormattedLibelle($format = "%g% %a% %l% %co% %ce%", $label_separator = ", ") {
        return ConfigurationProduitClient::getInstance()->format($this->getCepage()->getConfig()->getLibelles(), $this->labels->toArray(), $format);
    }

    public function getCepage() {
        return $this->getParent()->getParent();
    }

    public function getCouleur() {
        return $this->getCepage()->getCouleur();
    }

    public function getLieu() {
        return $this->getCouleur()->getLieu();
    }

    public function getMention() {
        return $this->getLieu()->getMention();
    }

    public function getAppellation() {
        return $this->getLieu()->getAppellation();
    }

    public function getGenre() {
        return $this->getAppellation()->getGenre();
    }

    public function getCertification() {
        return $this->getGenre()->getCertification();
    }

    public function getLabelKeyString() {
        if ($this->labels) {
            return implode('|', $this->labels->toArray());
        }

        return '';
    }

    public function hasLabel() {
    	if ($this->getLibelle() != $this->getGeneratedLibelle()) {
    		return false;
    	}
    	foreach ($this->labels as $label) {
    		if ($label) {
    			return true;
    		}
    	}
    	return false;
    }

    public function getLabelKey() {
        $key = null;
        if ($this->labels) {
            $key = implode('-', $this->labels->toArray());
        }
        return ($key) ? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelsLibelle($format = "%la%", $separator = ", ") {
        return str_replace("%la%", implode($separator, $this->libelles_label->toArray()), $format);
    }

    private function getTotalByKey($key) {
        $sum = 0;
        foreach ($this->get($key, true) as $k) {
            $sum += $k;
        }
        return $sum;
    }

    public function getTotalDebutMois() {
        if (is_null($this->_get('total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('total_debut_mois');
        }
    }

    public function getAcqTotalDebutMois() {
        if (is_null($this->_get('acq_total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('acq_total_debut_mois');
        }
    }

    public function getIdentifiantHTML() {
        return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }

    protected function update($params = array()) {
        parent::update($params);
        $configuration = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode());
        $this->total_entrees = round($this->sommeLignes(DRMVolumes::getEntreesSuspendus()),5);
        $this->total_sorties = round($this->sommeLignes(DRMVolumes::getSortiesSuspendus()),5);
        $this->total = round($this->total_debut_mois + $this->total_entrees - $this->total_sorties,5);
        $this->acq_total_entrees = round($this->sommeLignes(DRMVolumes::getEntreesAcquittes()),5);
        $this->acq_total_sorties = round($this->sommeLignes(DRMVolumes::getSortiesAcquittes()),5);
        $this->acq_total = round($this->acq_total_debut_mois + $this->acq_total_entrees - $this->acq_total_sorties,5);
        if ($this->has_vrac) {
            $this->total_debut_mois_interpro = $this->total_debut_mois;
            $this->total_entrees_interpro = $this->total_entrees;
            $this->total_sorties_interpro = $this->total_sorties;
            $this->total_entrees_nettes = round($this->sommeLignes(DRMVolumes::getEntreesNettes()),5);
            $this->total_entrees_reciproque = round($this->sommeLignes(DRMVolumes::getEntreesReciproque()),5);
            $this->total_sorties_nettes = round($this->sommeLignes(DRMVolumes::getSortiesNettes()),5);
            $this->total_sorties_reciproque = round($this->sommeLignes(DRMVolumes::getSortiesReciproque()),5);
            $this->total_interpro = round($this->total_debut_mois_interpro + $this->total_entrees_interpro - $this->total_sorties_interpro,5);
        }
        if (!$this->code) {
            $this->code = $this->getFormattedCode();
        }
        if (!$this->libelle) {
            $this->libelle = $this->makeFormattedLibelle("%g% %a% %l% %co% %ce%");
        }
        $labels = $this->labels->toArray();
        $labelLibelles = ConfigurationClient::getCurrent($this->getDocument()->getDateDebutPeriode())->getLabels();
        foreach ($labelLibelles as $code => $label) {
            if (in_array($code, $labels)) {
                $this->libelles_label->add($code, $label);
            }
        }
        $date = $this->getDocument()->periode . '-01';
        //if (!$this->cvo->taux) {
        $droitCvo = $this->getDroit(ConfigurationProduit::NOEUD_DROIT_CVO);
        if ($droitCvo) {
            $this->cvo->code = $droitCvo->code;
            $this->cvo->taux = $droitCvo->taux;
        } else {
            $this->cvo->code = null;
            $this->cvo->taux = 0;
        }
        //}
        //if (!$this->douane->taux) {
        $droitDouane = $this->getDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE);
        if ($droitDouane) {
            $this->douane->code = $droitDouane->code;
            $this->douane->taux = $droitDouane->taux;
        } else {
            $this->douane->code = null;
            $this->douane->taux = 0;
        }
        //}
        $this->storeInterpro();
        $this->cvo->volume_taxable = $this->getVolumeTaxable();
        $this->douane->volume_taxable = $this->getDouaneVolumeTaxable();
        $this->selecteur = 1;
    }

    private function getIsFacturableSortiesArray() {
        $mergeSorties = array();
        if (in_array($this->interpro, [Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID, Interpro::INTERPRO_KEY . Interpro::INTERVINS_SUD_EST_ID])) {
            $mergeSorties = DRMDroits::getDroitSortiesInterRhone();
        }
        return $mergeSorties;
    }

    private function getIsFacturableEntreeArray() {
        $mergeEntrees = array();
        if (in_array($this->interpro, [Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID, Interpro::INTERPRO_KEY . Interpro::INTERVINS_SUD_EST_ID])) {
            $mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
        }
        return $mergeEntrees;
    }

    private function getIsFacturableArray() {
        $mergeSorties = $this->getIsFacturableSortiesArray();
        $mergeEntrees = $this->getIsFacturableEntreeArray();
        return array_unique(array_merge(DRMDroits::getDroitSorties($mergeSorties), DRMDroits::getDroitEntrees($mergeEntrees)));
    }

    public function getVolumeTaxable() {
        $mergeSorties = $this->getIsFacturableSortiesArray();
        $mergeEntrees = $this->getIsFacturableEntreeArray();
        return ($this->sommeLignes(DRMDroits::getDroitSorties($mergeSorties)) - $this->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)));
    }

    public function getDouaneVolumeTaxable() {
        $mergeSorties = array();
        $mergeEntrees = array();
        if ($this->interpro == Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID) {
            $mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
        }
        return ($this->sommeLignes(DRMDroits::getDouaneDroitSorties()) - $this->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)));
    }

    public function nbToComplete() {
        return $this->hasMouvementCheck();
    }

    public function nbComplete() {
        return $this->isComplete();
    }

    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function addVrac($contrat_numero, $volume) {
        $contratVrac = $this->vrac->getOrAdd($contrat_numero);
        $old = floatval($contratVrac->volume);
        $contratVrac->volume = $old + ($volume * 1);
    }

    public function getContratsVracAutocomplete($prestation = false, $withSolde = false) {
        $vracs_autocomplete = array();
        $vracs = $this->getContratsVrac($withSolde);
        foreach ($vracs as $vrac) {
        	if ($vrac->exist('referente') && $vrac->referente === 0) {
        		continue;
        	}
            $acheteur = '';
            if ($vrac->acheteur->nom) {
                $acheteur .= $vrac->acheteur->nom;
                if ($vrac->acheteur->raison_sociale)
                    $acheteur .= ' ' . $vrac->acheteur->raison_sociale . '';
            } else {
                $acheteur .= $vrac->acheteur->raison_sociale;
            }
            $millesime = ($vrac->millesime) ? $vrac->millesime : 'Non millésimé';
            $courtier = '';
            if ($vrac->mandataire_exist) {
                if ($vrac->mandataire->nom) {
                    $courtier .= $vrac->mandataire->nom;
                    if ($vrac->mandataire->raison_sociale)
                        $courtier .= ' ' . $vrac->mandataire->raison_sociale . '';
                } else {
                    $courtier .= $vrac->mandataire->raison_sociale;
                }
            }
            $vol = $vrac->volume_propose - $vrac->volume_enleve;
            $vol = ($vol >= 0) ? $vol : 0;
            $id = $vrac->numero_contrat;
            if ($vrac->exist('version') && $vrac->version) {
            	$id .= '-'.$vrac->version;
            }
            if ($this->getDocument()->hasVersion() && $this->vrac->exist($id) && $this->getDocument()->getMother()->exist($this->getHash()) && $this->getDocument()->getMother()->get($this->getHash())->vrac->exist($id)) {
            	$vol += $this->getDocument()->getMother()->get($this->getHash())->vrac->get($id)->volume;
            }
            if ($prestation) {
            	$vracs_autocomplete[$id] = 'contrat n°' . $vrac->numero_contrat . ' comprenant ' . $vol . 'hl ' . $millesime;
            } else {
            	$vracs_autocomplete[$id] = $acheteur . ', contrat n°' . $vrac->numero_contrat . ' comprenant ' . $vol . 'hl à ' . $vrac->prix_unitaire . '€ HT/hl ' . $millesime . ' ' . $courtier;
            }
        }
        return $vracs_autocomplete;
    }

    public function getContratsVrac($withSolde = false) {
        $etablissement = 'ETABLISSEMENT-' . $this->getDocument()->identifiant;
        $contrats = VracClient::getInstance()->retrieveFromEtablissementsAndHash($etablissement, $this->getHash());
        if ($withSolde) {
        	$contrats = array_merge($contrats, VracClient::getInstance()->retrieveSoldeFromEtablissementsAndHash($etablissement, $this->getHash()));
        }
        return $contrats;
    }

    public function isModifiedMasterDRM($key) {

        return $this->getDocument()->isModifiedMasterDRM($this->getHash(), $key);
    }

    public function getDroit($type) {
        $date = $this->getDocument()->periode . '-01';
        return ($this->getCepage()->getConfig())? $this->getCepage()->getConfig()->getCurrentDroit($type, $date, true) : null;
    }

    public function canHaveVrac() {
    	if (!$this->getCepage()->getConfig()) {
    		return false;
    	}
        return ($this->getCepage()->getConfig()->getCurrentDrmVrac(true)) ? true : false;
    }

    public function hasCvo() {
        return ($this->getDroit(ConfigurationProduit::NOEUD_DROIT_CVO)) ? true : false;
    }

    public function hasDouane() {
        return ($this->getDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE)) ? true : false;
    }

    public function hasDetailLigne($ligne) {
        throw new sfException('fonction obsolete');
        return $this->getLieu()->hasDetailLigne($ligne);
    }

    protected function init($params = array()) {
        parent::init($params);
        $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
        $nextCampagne = isset($params['next_campagne']) ? $params['next_campagne'] : $this->getDocument()->campagne;

        $this->total_debut_mois = ($keepStock) ? $this->total : null;
        $this->acq_total_debut_mois = ($keepStock) ? $this->acq_total : null;
        $this->total_entrees = null;
        $this->total_sorties = null;
        $this->total = null;
        $this->acq_total_entrees = null;
        $this->acq_total_sorties = null;
        $this->acq_total = null;
        $this->total_entrees_nettes = null;
        $this->total_entrees_reciproque = null;
        $this->total_sorties_nettes = null;
        $this->total_sorties_reciproque = null;
        $this->total_debut_mois_interpro = ($keepStock) ? $this->total_interpro : null;
        $this->total_entrees_interpro = null;
        $this->total_sorties_interpro = null;
        $this->total_interpro = null;
        $this->observations = null;
        $this->selecteur = 1;
        $this->pas_de_mouvement_check = 0;
        if ($nextCampagne != $this->getDocument()->campagne) {
            $daids = DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->getDocument()->identifiant, $this->getDocument()->campagne);
            if ($daids) {
                if ($daids->exist($this->getHash())) {
                    $detailDAIDS = $daids->get($this->getHash());
                    $this->total_debut_mois = $detailDAIDS->stock_chais;
                    if ($this->has_vrac) {
                        $this->total_debut_mois_interpro = $detailDAIDS->stock_chais;
                    }
                }
            }
        }

        $this->remove('cvo');
        $this->add('cvo');
        $this->remove('douane');
        $this->add('douane');
        $this->remove('vrac');
        $this->add('vrac');

        $this->updateVolumeBloque();
    }

    public function sommeLignes($lines) {
        $sum = 0;
        foreach ($lines as $line) {
            $sum += $this->get($line);
        }
        return $sum;
    }

    public function hasStockFinDeMoisDRMPrecedente() {
        $result = false;
        $drmPrecedente = $this->getDocument()->getPrecedente();
        if ($drmPrecedente && !$drmPrecedente->isNew()) {
            if ($drmPrecedente->exist($this->getHash())) {
                if ($drmPrecedente->get($this->getHash())->total) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    /*
     * Fonction calculée
     */

    public function hasMouvement() {

        return ($this->total_entrees > 0 || $this->total_sorties > 0 || $this->acq_total_entrees > 0 || $this->acq_total_sorties > 0);
    }

    public function hasStockEpuise() {

        return $this->total_debut_mois == 0 && $this->acq_total_debut_mois == 0 && !$this->hasMouvement();
    }

    public function hasStockEpuiseByType($acq) {

        return ($acq)? ($this->acq_total_debut_mois == 0 && !($this->acq_total_entrees > 0 || $this->acq_total_sorties > 0)) : ($this->total_debut_mois == 0 && !($this->total_entrees > 0 || $this->total_sorties > 0));
    }

    public function hasMouvementCheck() {

        return !$this->pas_de_mouvement_check;
    }

    public function cascadingDelete() {
        $cepage = $this->getCepage();
        $couleur = $this->getCouleur();
        $lieu = $this->getLieu();
        $mention = $this->getMention();
        $appellation = $this->getAppellation();
        $genre = $this->getGenre();
        $certification = $this->getCertification();
        $objectToDelete = $this;
        if ($cepage->details->count() == 1 && $cepage->details->exist($this->getKey())) {
            $objectToDelete = $cepage;
            if ($couleur->cepages->count() == 1 && $couleur->cepages->exist($cepage->getKey())) {
                $objectToDelete = $couleur;
                if ($lieu->couleurs->count() == 1 && $lieu->couleurs->exist($couleur->getKey())) {
                    $objectToDelete = $lieu;
                    if ($mention->lieux->count() == 1 && $mention->lieux->exist($lieu->getKey())) {
                        $objectToDelete = $mention;
                        if ($appellation->mentions->count() == 1 && $appellation->mentions->exist($mention->getKey())) {
                            $objectToDelete = $appellation;
                            if ($genre->appellations->count() == 1 && $genre->appellations->exist($appellation->getKey())) {
                                $objectToDelete = $genre;
                                if ($certification->genres->count() == 1 && $certification->genres->exist($genre->getKey())) {
                                    $objectToDelete = $certification;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $objectToDelete;
    }



    public function cascadingFictiveDelete() {
        $cepage = $this->getCepage();
        $couleur = $this->getCouleur();
        $lieu = $this->getLieu();
        $mention = $this->getMention();
        $appellation = $this->getAppellation();
        $genre = $this->getGenre();
        $objectToDelete = $this;
        if ($cepage->details->count() == 1 && $cepage->details->exist($this->getKey())) {
            $objectToDelete = $cepage;
            if ($couleur->cepages->count() == 1 && $couleur->cepages->exist($cepage->getKey())) {
                $objectToDelete = $couleur;
                if ($lieu->couleurs->count() == 1 && $lieu->couleurs->exist($couleur->getKey())) {
                    $objectToDelete = $lieu;
                    if ($mention->lieux->count() == 1 && $mention->lieux->exist($lieu->getKey())) {
                        $objectToDelete = $mention;
                        if ($appellation->mentions->count() == 1 && $appellation->mentions->exist($mention->getKey())) {
                            $objectToDelete = $appellation;
                            if ($genre->appellations->count() == 1 && $genre->appellations->exist($appellation->getKey())) {
                                $objectToDelete = $genre;
                            }
                        }
                    }
                }
            }
        }
        return $objectToDelete;
    }

    public function getStockTheoriqueMensuelByCampagne($campagne) {
        $drmsHistorique = new DRMHistorique($this->getDocument()->identifiant);
        $drms = $drmsHistorique->getDRMsPeriodeByCampagne($campagne);
        $total = 0;
        $nbDrm = 0;
        foreach ($drms as $periode) {
            if ($drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->getDocument()->identifiant, $periode)) {
                if ($drm->exist($this->getHash())) {
                    $nbDrm++;
                    $detail = $drm->get($this->getHash());
                    $total += $detail->total;
                }
            }
        }
        return ($nbDrm > 0) ? ($total / $nbDrm) : 0;
    }

    public function updateVolumeBloque() {
        $produitHash = str_replace('/declaration/', '', $this->getCepage()->getHash());
        $produitHash = str_replace('/', '_', $produitHash);
        $etablissement = $this->getDocument()->getEtablissement();
        $date = $this->getDocument()->periode . '-01';
        if ($etablissement->produits->exist($produitHash)) {
            $this->stocks_debut->bloque = $etablissement->getVolumeBloque($produitHash, $date);
        }
    }

    public function storeInterpro() {
        if ($config = $this->getConfig()) {
            $this->add('interpro', $config->getDocument()->interpro);
        } else {
            $this->add('interpro', null);
        }
    }

    public function getMouvements() {
        return array_replace_recursive($this->getMouvementsByNoeud('entrees'), $this->getMouvementsByNoeud('sorties'));
    }

    public function getMouvementsByNoeud($hash) {
        $mouvements = array();
        foreach ($this->get($hash) as $key => $volume) {
            if ($volume instanceof acCouchdbJson) {
                continue;
            }
            $type = (strpos($key, 'acq_') === false)? 'SUSPENDU' : 'ACQUITTE';
            $facturableArray = $this->getIsFacturableArray();
            $mouvement = DRMMouvement::freeInstance($this->getDocument());
            $mouvement->produit_hash = $this->getHash();
            $mouvement->produit_libelle = trim($mouvement->produit_libelle);
            if (count($this->labels) > 0 && $this->labels[0] !== null) {
                $mouvement->denomination_complementaire = implode(', ', $this->labels->toArray());
                $mouvement->produit_libelle .= ' '.$mouvement->denomination_complementaire;
            }
            $mouvement->produit_libelle = trim($mouvement->produit_libelle);
            $mouvement->type_drm = $type;
            $mouvement->type_drm_libelle = ucfirst(strtolower($type));
            $mouvement->facture = 0;
            $mouvement->interpro = $this->interpro;
            $etablissement = $this->getDocument()->getEtablissementObject();
            if ($etablissement && ($societe = $etablissement->getSociete())) {
                $mouvement->region = $societe->getRegionViticole();
            } else {
                $mouvement->region = EtablissementClient::REGION_HORS_CVO;
            }
            $mouvement->cvo = $this->getCVOTaux();
            if (!$this->getDocument()->isProducteur()) {
              $mouvement->facturable = 0;
            } else {
              $mouvement->facturable = in_array($hash . '/' . $key, $facturableArray) ? 1 : 0;
            }
            if ($mouvement->cvo <= 0) {
                $mouvement->facturable = 0;
            }
            if (!in_array($mouvement->interpro, ['INTERPRO-IR','INTERPRO-CIVP','INTERPRO-IVSE'])) {
                $mouvement->region = EtablissementClient::REGION_HORS_CVO;
            }
            $mouvement->version = $this->getDocument()->getVersion();
            $mouvement->date_version = ($this->getDocument()->valide->date_saisie) ? ($this->getDocument()->valide->date_saisie) : date('Y-m-d');
            $mouvement->categorie = FactureClient::FACTURE_LIGNE_MOUVEMENT_TYPE_PROPRIETE;
            $mouvement = $this->createMouvement(clone $mouvement, $hash . '/' . $key, $volume);
            if (!$mouvement) {
                continue;
            }
            if (is_array($mouvement)) {
                foreach ($mouvement as $mouvement_vrac) {
                    $mouvements[$this->getDocument()->getIdentifiant()][$mouvement_vrac->getMD5Key()] = $mouvement_vrac;
                }
            } else {
                $mouvements[$this->getDocument()->getIdentifiant()][$mouvement->getMD5Key()] = $mouvement;
            }
        }

        return $mouvements;
    }

    public function createMouvement($mouvement, $hash, $volume) {
        if ($this->getDocument()->hasVersion() && !$this->getDocument()->isModifiedMother($this, $hash)) {
            return null;
        }

        $mouvement->type_hash = $hash;

        if ($this->getDocument()->hasVersion() && $this->getDocument()->motherExist($this->getHash() . '/' . $hash)) {
            $volume = $volume - $this->getDocument()->motherGet($this->getHash() . '/' . $hash);
        }

        $configCoeffMouvement = Configuration::getAllStocksCoeffsMouvements();
		if (!isset($configCoeffMouvement [$hash])) {
			return null;
		}
        $volume = $configCoeffMouvement [$hash] * $volume;

        if ($volume == 0) {
            return null;
        }

        $stocksLibelles = Configuration::getAllStocksLibelles();

        $mouvement->type_libelle = $stocksLibelles[$hash];

        $mouvement->volume = $volume;
        $mouvement->date = $this->getDocument()->getDate();

        return $mouvement

        ;
    }

    public function getCVOTaux() {
        return $this->cvo->taux

        ;
    }

    public function hasVracs() {
        if (isset($this->vrac[null])) {
            unset($this->vrac[null]);
        }
        return count($this->vrac);
    }

    public function hasSortieVrac() {
        return ($this->getTotalVrac() > 0) ? true : false;
    }

    public function getTotalVrac() {
      return $this->sorties->vrac + $this->sorties->vrac_export;
    }

    public function getStockBilan()
    {
    	return $this->getTotalVrac() + $this->sorties->export + $this->sorties->factures + $this->sorties->crd + $this->sorties->consommation + $this->sorties->pertes;
    }

    public function getLibelleFiscal($guess_if_empty = false)
    {
    	$lib = $this->getCepage()->getLibelleFiscal();
        if ($lib || !$guess_if_empty) {
            return $lib;
        }
        if (!$this->isInao()) {
            return null;
        }
        if (preg_match('/^3[RBS][^M]*$/', $this->getInao())) {
            return 'VT_IG_IGP';
        }
        if (preg_match('/^1[RBS][^M]*$/', $this->getInao())) {
            return 'VT_IG_AOC';
        }
        if (preg_match('/^3[RBS].*M/', $this->getInao())) {
            return 'VM_IG_IGP';
        }
        if (preg_match('/^1[RBS].*M/', $this->getInao())) {
            return 'VM_IG_AOC';
        }
        return null;
    }

    public function isInao() {
        return preg_match('/^[0-9]/', $this->getInao());
    }

    public function getInao()
    {
      $inao = null;
      if ($this->exist('inao')) {
        $inao = $this->_get('inao');
  			if (strlen($inao) == 5) {
  				$inao = $inao.' ';
  			}
  			$this->setInao($inao);
      }
    	return ($inao)? $inao : $this->getCepage()->getInao();
    }

    public function getIdentifiantDouane()
    {
    	$inao = $this->getInao();
    	if (!$inao) {
    		return $this->getLibelleFiscal();
    	}
    	return $inao;
    }
    public function getHasSaisieAcq() {
    	$has = false;
    	if ($this->acq_total_debut_mois || $this->acq_total_entrees || $this->acq_total_sorties) {
    		$has = true;
    	}
    	return $has;
    }

    public function getRetiraisons() {
    	$retiraisons = array();
    	if (($this->sorties->vrac && $this->canHaveVrac()) || count($this->vrac->toArray()) > 0) {
    		foreach ($this->vrac as $id => $vrac) {
    			$retiraisons[$id] = $vrac->volume;
    		}
    	}
    	return $retiraisons;
    }


    public function getTotalVolume($hashes) {
    	$total = null;
    		foreach ($hashes as $hash) {
    			if ($this->exist($hash)) {
    				$total += $this->getOrAdd($hash);
    			} else {
    				$total += null;
    			}
    		}
    	return $total;
    }

    public function setImportableObservations($observations) {
      if ($this->observations) {
        $observations = $this->observations.', '.$observations;
      }
    	$this->observations = $observations;
    }

    public function isVci() {
    	return ($this->getGenre()->getKey() == 'VCI');
    }

    public function isCleanable($acq = false) {
      if ($this->getDocument()->hasVersion()) {
        return false;
      }
      if ($acq) {
        return ($this->acq_total_debut_mois == 0 && $this->acq_total_entrees == 0 && $this->acq_total_sorties == 0);
      } else {
        return ($this->total_debut_mois == 0 && $this->total_entrees == 0 && $this->total_sorties == 0);
      }
    }
}
