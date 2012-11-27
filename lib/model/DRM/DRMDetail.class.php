<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
    
    public function getConfig() {
    	
    	return ConfigurationClient::getCurrent()->declaration->certifications->get($this->getCertification()->getKey())->detail;
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") {

    	return $this->getCepage()->getConfig()->getLibelleFormat($this->labels->toArray(), $format, $label_separator);
    }

    public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") {

    	return $this->getCepage()->getConfig()->getCodeFormat($format);
    }
    
    /**
     *
     * @return DRMCepage
     */
    public function getCepage() {

        return $this->getParent()->getParent();
    }

    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {

        return $this->getCepage()->getCouleur();
    }

    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {

        return $this->getCouleur()->getLieu();
    }

    /**
     *
     * @return DRMMention
     */
    public function getMention() {

        return $this->getLieu()->getMention();
    }

    /**
     *
     * @return DRMAppellation
     */
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

    
    protected function update($params = array()) {
        parent::update($params);
        $this->total_entrees = $this->getTotalByKey('entrees');
        $this->total_sorties = $this->getTotalByKey('sorties');
        $this->total = $this->total_debut_mois + $this->total_entrees - $this->total_sorties;
        $this->code = $this->getFormattedCode();
        $this->libelle = $this->getFormattedLibelle("%g% %a% %l% %co% %ce%");
        $labelLibelles = $this->getConfig()->getDocument()->getLabelsLibelles($this->labels->toArray());
        foreach ($labelLibelles as $label => $libelle) {
        	$this->libelles_label->add($label, $libelle);
        }
        $this->cvo->taux = $this->getDroit(DRMDroits::DROIT_CVO)->getTaux();
        $this->douane->taux = $this->getDroit(DRMDroits::DROIT_DOUANE)->getTaux();
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

    public function nbToComplete() {
    	return $this->hasMouvementCheck();
    }

    public function nbComplete() {
    	return $this->isComplete();
    }
    
    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }
    
    public function getIdentifiantHTML() {
      return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }	
    
    public function addVrac($contrat_numero, $volume) {
      $contratVrac = $this->vrac->add($contrat_numero."");
      $contratVrac->volume = $volume*1 ;
    }

    public function getContratsVracAutocomplete() {
      $vracs_autocomplete = array();
      $vracs = $this->getContratsVrac();
      foreach ($vracs as $vrac) {
    	$acheteur = '';
      	if ($vrac->acheteur->nom) {
      		$acheteur .= $vrac->acheteur->nom; 
	      	if ($vrac->acheteur->raison_sociale) 
	      		$acheteur .=  ' '.$vrac->acheteur->raison_sociale.''; 
      	} else {
      		$acheteur .= $vrac->acheteur->raison_sociale;
      	}
      	$millesime = ($vrac->millesime)? $vrac->millesime : 'Non millésimé';
      	$courtier = '';
      	if ($vrac->mandataire_exist) {
	      	if ($vrac->mandataire->nom) {
	      		$courtier .= $vrac->mandataire->nom; 
		      	if ($vrac->mandataire->raison_sociale) 
		      		$courtier .=  ' '.$vrac->mandataire->raison_sociale.''; 
	      	} else {
	      		$courtier .= $vrac->mandataire->raison_sociale;
	      	}
      	}
        $vracs_autocomplete[$vrac->numero_contrat] = $acheteur.' '.$vrac->numero_contrat.' ('.$vrac->volume_propose.' hl proposés, '.($vrac->volume_propose - $vrac->volume_enleve).' hl restants à '.$vrac->prix_unitaire.'€ ht/hl) '.$millesime.' '.$courtier;
      }
      return $vracs_autocomplete;
    }

    public function getContratsVrac() {
    	$etablissement = 'ETABLISSEMENT-'.$this->getDocument()->identifiant;
    	return VracClient::getInstance()->retrieveFromEtablissementsAndHash($etablissement, $this->getHash());
    }

    public function isModifiedMasterDRM($key) {
      
      return $this->getDocument()->isModifiedMasterDRM($this->getHash(), $key);
    }


    public function getDroitVolume($type) {
      return $this->sommeLignes(DRMDroits::getDroitSorties()) - $this->sommeLignes(DRMDroits::getDroitEntrees());
    }

    public function getDroit($type) {
    	if ($droit = $this->getCouleur()->getDroit($type)) {
    		return $droit;
    	}
    	if ($droit = $this->getLieu()->getDroit($type)) {
    		return $droit;
    	}
    	if ($droit = $this->getAppellation()->getDroit($type)) {
    		return $droit;
    	}
    	if ($droit = $this->getGenre()->getDroit($type)) {
    		return $droit;
    	}
    	if ($droit = $this->getCertification()->getDroit($type)) {
    		return $droit;
    	}
    	if (!$droit) {
    		throw new sfException('Aucun droit spécifié');
    	}
    }
    
    public function hasCvo()
    {
    	$cvo = $this->getDroit('cvo');
    	return !$cvo->isEmpty();
    }
    
    public function hasDouane()
    {
    	$douane = $this->getDroit('douane');
    	return !$douane->isEmpty();
    }
    
    public function hasDetailLigne($ligne)
    {
    	return $this->getLieu()->hasDetailLigne($ligne);
    }

    protected function init($params = array()) {
      parent::init($params);
      
      $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
	
      $this->total_debut_mois = ($keepStock)? $this->total : null;
      $this->total_entrees = null;
      $this->total_sorties = null;
      $this->total = null;

      $this->remove('vrac');
      $this->add('vrac');
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($lines as $line) {
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

        return $this->total_entrees > 0 || $this->total_sorties > 0;
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
    
    public function getStockTheoriqueMensuelByCampagne($campagne)
    {
    	$drmsHistorique = new DRMHistorique($this->getDocument()->identifiant);
    	$drms = $drmsHistorique->getDRMsByCampagne($campagne);
    	$total = 0;
    	$nbDrm = 0;
    	foreach ($drms as $d) {
    		$drm = DRMClient::getInstance()->find($d->_id);
    		if ($drm->exist($this->getHash())) {
    			$nbDrm++;
    			$detail = $drm->get($this->getHash());
    			$total += $detail->total;
    		}
    	}
    	return ($nbDrm > 0)? ($total / $nbDrm) : 0;
    }
    
    
}