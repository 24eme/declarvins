<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

    protected $_config = null;

    public function getConfig() {
        if (!$this->_config) {
            $this->_config = ConfigurationClient::getCurrent()->getConfigurationProduit($this->getCepage()->getHash());
        }
        return $this->_config;
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {
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
        $configuration = ConfigurationClient::getCurrent();
        $this->total_entrees = round($this->sommeLignes(DRMVolumes::getEntreesSuspendus()), 4);
        $this->total_sorties = round($this->sommeLignes(DRMVolumes::getSortiesSuspendus()), 4);
        $this->total = round($this->total_debut_mois + $this->total_entrees - $this->total_sorties, 4);
        $this->acq_total_entrees = round($this->sommeLignes(DRMVolumes::getEntreesAcquittes()), 4);
        $this->acq_total_sorties = round($this->sommeLignes(DRMVolumes::getSortiesAcquittes()), 4);
        $this->acq_total = round($this->acq_total_debut_mois + $this->acq_total_entrees - $this->acq_total_sorties, 4);
        if ($this->has_vrac) {
            $this->total_debut_mois_interpro = $this->total_debut_mois;
            $this->total_entrees_interpro = $this->total_entrees;
            $this->total_sorties_interpro = $this->total_sorties;
            $this->total_entrees_nettes = round($this->sommeLignes(DRMVolumes::getEntreesNettes()), 4);
            $this->total_entrees_reciproque = round($this->sommeLignes(DRMVolumes::getEntreesReciproque()), 4);
            $this->total_sorties_nettes = round($this->sommeLignes(DRMVolumes::getSortiesNettes()), 4);
            $this->total_sorties_reciproque = round($this->sommeLignes(DRMVolumes::getSortiesReciproque()), 4);
            $this->total_interpro = round($this->total_debut_mois_interpro + $this->total_entrees_interpro - $this->total_sorties_interpro, 4);
        }
        if (!$this->code) {
            $this->code = $this->getFormattedCode();
        }
        if (!$this->libelle) {
            $this->libelle = $this->makeFormattedLibelle("%g% %a% %l% %co% %ce%");
        }
        $labels = $this->labels->toArray();
        $labelLibelles = ConfigurationClient::getCurrent()->getLabels();
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
        if ($this->interpro == Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID) {
            $mergeSorties = DRMDroits::getDroitSortiesInterRhone();
        }
        return $mergeSorties;
    }

    private function getIsFacturableEntreeArray() {
        $mergeEntrees = array();
        if ($this->interpro == Interpro::INTERPRO_KEY . Interpro::INTER_RHONE_ID) {
            $mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
        }
        return $mergeEntrees;
    }

    private function getIsFacturableArray() {
        $mergeSorties = $this->getIsFacturableSortiesArray();
        $mergeEntrees = $this->getIsFacturableEntreeArray();
        return array_unique(array_merge(DRMDroits::getDroitSorties($mergeSorties), DRMDroits::getDroitSorties($mergeEntrees)));
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
        $contratVrac->volume = $volume * 1;
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

        return $this->total_debut_mois == 0 && !$this->hasMouvement();
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

            $facturableArray = $this->getIsFacturableArray();
            if (!$this->getCepage()->getConfig()) {
            	continue;
            }

            $mouvement = DRMMouvement::freeInstance($this->getDocument());
            $mouvement->produit_hash = $this->getCepage()->getConfig()->getHash();
            $mouvement->facture = 0;
            $mouvement->interpro = $this->interpro;
            $mouvement->cvo = $this->getCVOTaux();
            $mouvement->facturable = in_array($hash . '/' . $key, $facturableArray) ? 1 : 0;
            $mouvement->version = $this->getDocument()->getVersion();
            $mouvement->date_version = ($this->getDocument()->valide->date_saisie) ? ($this->getDocument()->valide->date_saisie) : date('Y-m-d');


//            if ($this->exist($hash . "/" . $key . "_details")) {
//                $mouvements = array_replace_recursive($mouvements, $this->get($hash . "/" . $key . "_details")->createMouvements($mouvement));
//                continue;
//            }

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

        if ($hash == "sorties/vrac" && $this->hasVracs()) {
            $mouvements_vrac = array();
            foreach ( $this->vrac as $vrac_numero => $vrac) {
                $mouvement_vrac = clone $mouvement;
                $hash_vrac = "sorties/vrac_contrat";
                $mouvement_vrac->type_hash = $hash_vrac;
                $volume_vrac = $configCoeffMouvement [$hash_vrac] * $vrac->volume;
                $mouvement_vrac->type_libelle = $stocksLibelles[$hash_vrac];

                if ($volume_vrac == 0) {
                    return null;
                }
                $mouvement_vrac->volume = $volume_vrac;
                $mouvement_vrac->date = $this->getDocument()->getDate();
                $mouvement_vrac->vrac_numero = $vrac_numero;

                $mouvements_vrac[] = $mouvement_vrac;

                $mouvement->volume -= $volume_vrac;
            }
            $mouvement->volume = $volume;
            $mouvement->date = $this->getDocument()->getDate();
            $mouvements_vrac[] = $mouvement;
            return $mouvements_vrac;
        }

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
        return count($this->vrac);
    }

    public function hasSortieVrac() {
        return ($this->sorties->vrac > 0) ? true : false;
    }

    public function getStockBilan()
    {
    	return $this->sorties->vrac + $this->sorties->export + $this->sorties->factures + $this->sorties->crd + $this->sorties->consommation + $this->sorties->pertes;
    }
	
    public function getLibelleFiscal()
    {
    	return $this->getCepage()->getLibelleFiscal();
    }
	
    public function getInao()
    {
    	return $this->getCepage()->getInao();
    }
    public function getHasSaisieAcq() {
    	$has = false;
    	if ($this->acq_total_debut_mois || $this->acq_total_entrees || $this->acq_total_sorties) {
    		$has = true;
    	}
    	return $has;
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
    	$this->add('observations', $observations);
    }
}
