<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
    
	protected $_config = null;
	
    public function getConfig() 
    {
    	if (!$this->_config) {
    		$this->_config = ConfigurationClient::getCurrent()->declaration->certifications->get($this->getCertification()->getKey())->detail;
    	}
    	return $this->_config;
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce% <span class=\"labels\">%la%</span>", $label_separator = ", ") 
    {
    	return $this->getCepage()->getConfig()->getLibelleFormat($this->labels->toArray(), $format, $label_separator);
    }

    public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") 
    {
    	return $this->getCepage()->getConfig()->getCodeFormat($format);
    }
    
    public function getCepage() 
    {
        return $this->getParent()->getParent();
    }
    
    public function getCouleur() 
    {
        return $this->getCepage()->getCouleur();
    }
    
    public function getLieu() 
    {
        return $this->getCouleur()->getLieu();
    }
    
    public function getMention() 
    {
        return $this->getLieu()->getMention();
    }
    
    public function getAppellation() 
    {
        return $this->getLieu()->getAppellation();
    }

    public function getGenre()
    {
      return $this->getAppellation()->getGenre();
    }

    public function getCertification() 
    {
      return $this->getGenre()->getCertification();
    }
    
    public function getLabelKeyString() 
    {
      	if ($this->labels) {
			return implode('|', $this->labels->toArray());
      	}

      	return '';
    }

    public function getLabelKey() 
    {
    	$key = null;
    	if ($this->labels) {
    		$key = implode('-', $this->labels->toArray());
    	}
    	return ($key) ? $key : DRM::DEFAULT_KEY;
    }

	public function getLabelsLibelle($format = "%la%", $separator = ", ") 
	{
      	return str_replace("%la%", implode($separator, $this->libelles_label->toArray()), $format);
    } 
    
    private function getTotalByKey($key) 
    {
    	$sum = 0;
    	foreach ($this->get($key, true) as $k) {
    		$sum += $k;
    	}
    	return $sum;
    }

    public function getTotalDebutMois() 
    {
        if (is_null($this->_get('total_debut_mois'))) {
            return 0;
        } else {
            return $this->_get('total_debut_mois');
        }
    }
    
    public function getIdentifiantHTML() 
    {
      return strtolower(str_replace($this->getDocument()->declaration->getHash(), '', str_replace('/', '_', preg_replace('|\/[^\/]+\/DEFAUT|', '', $this->getHash()))));
    }	

    
    protected function update($params = array()) {
        parent::update($params);
        $this->total_entrees = $this->getTotalByKey('entrees');
        $this->total_sorties = $this->getTotalByKey('sorties');
        $this->total = $this->total_debut_mois + $this->total_entrees - $this->total_sorties;
        if (!in_array(substr($this->getCepage()->getHash(),1), ConfigurationClient::getInstance()->findHashProduitsNoCvo($this->getDocument()->getInterpro()->getKey()))) {
        	$this->total_debut_mois_net = $this->total_debut_mois;
        	$this->total_entrees_nettes = $this->sommeLignes(DRMVolumes::getEntreesNettes());
        	$this->total_entrees_reciproque = $this->sommeLignes(DRMVolumes::getEntreesReciproque());
        	$this->total_sorties_nettes = $this->sommeLignes(DRMVolumes::getSortiesNettes());
        	$this->total_sorties_reciproque = $this->sommeLignes(DRMVolumes::getSortiesReciproque());
        	$this->total_net = $this->total_entrees_nettes + $this->total_entrees_reciproque + $this->total_entrees_reciproque - $this->total_sorties_nettes - $this->total_sorties_reciproque;
        }
        if (!$this->code) {
        	$this->code = $this->getFormattedCode();
        }
        if (!$this->libelle) {
        	$this->libelle = $this->getFormattedLibelle("%g% %a% %l% %co% %ce%");
        }
        $labelLibelles = $this->getConfig()->getDocument()->getLabelsLibelles($this->labels->toArray());
        foreach ($labelLibelles as $label => $libelle) {
        	$this->libelles_label->add($label, $libelle);
        }
        if (!$this->cvo->taux) {
        	$droitCvo = $this->getDroit(DRMDroits::DROIT_CVO);
	        if ($droitCvo) {
	        	$this->cvo->code = $droitCvo->code;
	        	$this->cvo->taux = $droitCvo->taux;
	        } else {
	        	$this->cvo->code = null;
	        	$this->cvo->taux = 0;
	        }
        }
        if (!$this->douane->taux) {
        	$droitDouane = $this->getDroit(DRMDroits::DROIT_DOUANE);
	        if ($droitDouane) {
	        	$this->douane->code = $droitDouane->code;
	        	$this->douane->taux = $droitDouane->taux;
	        } else {
	        	$this->douane->code = null;
	        	$this->douane->taux = 0;
	        }
        }
        $this->cvo->volume_taxable = $this->getVolumeTaxable();
        $this->douane->volume_taxable = $this->getDouaneVolumeTaxable();
        $this->selecteur = 1;
    }
    
    public function getVolumeTaxable()
    {
    	$mergeSorties = array();
    	$mergeEntrees = array();
    	if ($this->getDocument()->getInterpro()->getKey() == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
    		$mergeSorties = DRMDroits::getDroitSortiesInterRhone();
    		$mergeEntrees = DRMDroits::getDroitEntreesInterRhone();
    	}
    	return ($this->sommeLignes(DRMDroits::getDroitSorties($mergeSorties)) - $this->sommeLignes(DRMDroits::getDroitEntrees($mergeEntrees)));
    }
    
    public function getDouaneVolumeTaxable()
    {
    	$mergeSorties = array();
    	$mergeEntrees = array();
    	if ($this->getDocument()->getInterpro()->getKey() == Interpro::INTERPRO_KEY.Interpro::INTER_RHONE_ID) {
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
        $vracs_autocomplete[$vrac->numero_contrat] = $acheteur.', contrat n°'.$vrac->numero_contrat.' comprenant '.($vrac->volume_propose - $vrac->volume_enleve).'hl à '.$vrac->prix_unitaire.'€ HT/hl '.$millesime.' '.$courtier;
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

    public function getDroit($type) {
    	return ConfigurationClient::getInstance()->getDroitsByHashAndTypeAndPeriode($this->getCepage()->getHash(), $type, $this->getDocument()->getPeriode().'-01');
    }
    
    public function canHaveVrac()
    {
    	return ($this->getCepage()->getConfig()->has_vrac)? true : false;
    }
    
    public function hasCvo()
    {
    	return ($this->getDroit(DRMDroits::DROIT_CVO))? true : false;
    }
    
    public function hasDouane()
    {
    	return ($this->getDroit(DRMDroits::DROIT_DOUANE))? true : false;
    }
    
    public function hasDetailLigne($ligne)
    {
    	return $this->getLieu()->hasDetailLigne($ligne);
    }

    protected function init($params = array()) {
      parent::init($params);
      $keepStock = isset($params['keepStock']) ? $params['keepStock'] : true;
	  $nextCampagne = isset($params['next_campagne']) ? $params['next_campagne'] : $this->getDocument()->campagne;
	  
      $this->total_debut_mois = ($keepStock)? $this->total : null;
      $this->total_entrees = null;
      $this->total_sorties = null;
      $this->total = null;
      $this->total_debut_mois_net = ($keepStock)? $this->total_net : null;
      $this->total_entrees_nettes = null;
      $this->total_entrees_reciproque = null;
      $this->total_sorties_nettes = null;
      $this->total_sorties_reciproque = null;
      $this->total_net = null;
      $this->cvo->taux = null;
      $this->douane->taux = null;
      $this->selecteur = 1;
       if ($nextCampagne != $this->getDocument()->campagne) {
       	$daids = DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->getDocument()->identifiant, $this->getDocument()->campagne);
       	if ($daids) {
       		if ($daids->exist($this->getHash())) {
       			$detailDAIDS = $daids->get($this->getHash());
       			$this->total_debut_mois = $detailDAIDS->stock_chais;
       			$this->total_debut_mois_net = $detailDAIDS->stock_chais;
       		}
       	}
       	$this->pas_de_mouvement_check = 0;
       }
	  
      $this->remove('vrac');
      $this->add('vrac');
      
      $this->updateVolumeBloque();
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
    
    public function updateVolumeBloque()
    {
    	$produitHash =  str_replace('/declaration/', '', $this->getCepage()->getHash());
      	$produitHash = str_replace('/', '_', $produitHash);
      	$etablissement = $this->getDocument()->getEtablissement();
      	if ($etablissement->produits->exist($produitHash)) {
      		$this->stocks_debut->bloque = $etablissement->produits->get($produitHash)->volume_bloque;
      	}
    }
    
}