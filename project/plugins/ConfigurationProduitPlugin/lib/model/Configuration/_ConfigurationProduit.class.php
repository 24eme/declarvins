<?php
abstract class _ConfigurationProduit extends acCouchdbDocumentTree 
{
	protected $libelles = null;
	protected $codes = null;

  	public function getParentNode() 
  	{
		$parent = $this->getParent()->getParent();
		if ($parent->getKey() == 'declaration') {
			throw new sfException('Noeud racine atteint');
		} else {
			return $this->getParent()->getParent();
		}
	}
	
	public function getProduits() 
	{       
      	$produits = array();
      	foreach($this->getChildrenNode() as $key => $item) {
        	$produits = array_merge($produits, $item->getProduits());
      	}
    	return $produits;
  	}
    
    public function getAllAppellations()
    {
    	$items = array();
      	foreach($this->getChildrenNode() as $key => $item) {
        	$items = array_merge($items, $item->getAllAppellations());
      	}
    	return $items;
    }
    
    public function getAllLieux()
    {
    	$items = array();
      	foreach($this->getChildrenNode() as $key => $item) {
        	$items = array_merge($items, $item->getAllLieux());
      	}
    	return $items;
    }
    
    public function getAllCepages()
    {
    	$items = array();
      	foreach($this->getChildrenNode() as $key => $item) {
        	$items = array_merge($items, $item->getAllCepages());
      	}
    	return $items;
    }
    
    protected function castFloat($float) 
    {
    	return floatval(str_replace(',', '.', $float));
    }
    
    public abstract function getChildrenNode();
    

    /*
     * Les fonctions ci-dessous permettent la récupération de la configuration d'un produit
     */
    public function getCurrentDroit($typeDroit, $atDate = null)
    {
    	$atDate = ($atDate)? $atDate : date('Y-m-d');
    	if ($this->exist('droits')) {
    		$droits = $this->droits->get($typeDroit)->toArray();
    		if (count($droits) > 0) {
    			krsort($droits);
    			$d = null;
    			foreach ($droits as $date => $droit) {
    				if ($date <= $atDate) {
    					$d = $droit;
    				}
    			}
    			if ($d) {
    				return array($this->getTypeNoeud() => $d);
    			}
    		}
    	}
    	return $this->callbackCurrentDroit($typeDroit, $atDate);
    }
    
    public function callbackCurrentDroit($typeDroit, $atDate = null)
    {
    	return $this->getParentNode()->getCurrentDroit($typeDroit, $atDate);
    }
    
	public function getHistoryDroit($typeDroit)
    {
    	$result = array();
    	if ($this->exist('droits')) {
    		$droits = $this->droits->get($typeDroit)->toArray();
    		if (count($droits) > 0) {
    			$result = array($this->getCodeApplicatif() => $droits);
    		}
    	}
    	return array_merge($this->callbackHistoryDroit($typeDroit), $result);
    }
    
    public function callbackHistoryDroit($typeDroit)
    {
    	return $this->getParentNode()->getHistoryDroit($typeDroit);
    }
    
    public function getCurrentDepartements()
    {
    	if ($this->exist('departements')) {
    		$departements = $this->departements->toArray();
    		if (count($departements) > 0) {
    			return array($this->getTypeNoeud() => $departements);
    		}
    	}
    	return $this->callbackCurrentDepartements();
    }
    
    public function callbackCurrentDepartements()
    {
    	return $this->getParentNode()->getCurrentDepartements();
    }
    
    public function getCurrentLabels()
    {
    	if ($this->exist('labels')) {
    		$labels = $this->labels->toArray();
    		if (count($labels) > 0) {
    			return array($this->getTypeNoeud() => $labels);
    		}
    	}
    	return $this->callbackCurrentLabels();
    }
    
    public function callbackCurrentLabels()
    {
    	return $this->getParentNode()->getCurrentLabels();
    }
    
    public function getCurrentDrmVrac()
    {
    	if ($this->exist('drm_vrac')) {
    		$drm_vrac = ($this->drm_vrac)? 1 : 0;
    		return array($this->getTypeNoeud() => $drm_vrac);
    	}
    	return $this->callbackCurrentDrmVrac();
    }
    
    public function callbackCurrentDrmVrac()
    {
    	return $this->getParentNode()->getCurrentDrmVrac();
    }
    
    public function getCurrentOrganisme($atDate = null)
    {
    	$atDate = ($atDate)? $atDate : date('Y-m-d');
    	if ($this->exist('organismes')) {
    		$organismes = $this->organismes->toArray();
    		if (count($organismes) > 0) {
    			krsort($organismes);
    			$o = null;
    			foreach ($organismes as $date => $organisme) {
    				if ($date <= $atDate) {
    					$o = $organisme;
    				}
    			}
    			if ($o) {
    				return array($this->getTypeNoeud() => $o);
    			}
    		}
    	}
    	return $this->callbackCurrentOrganisme($atDate);
    }
    
    public function callbackCurrentOrganisme($atDate = null)
    {
    	return $this->getParentNode()->getCurrentOrganisme();
    }
    
	public function getHistoryOrganisme()
    {
    	$result = array();
    	if ($this->exist('organismes')) {
    		$organismes = $this->organismes->toArray();
    		if (count($organismes) > 0) {
    			$result = array($this->getCodeApplicatif() => $organismes);
    		}
    	}
    	return array_merge($this->callbackHistoryOrganisme(), $result);
    }
    
    public function callbackHistoryOrganisme()
    {
    	return $this->getParentNode()->getHistoryOrganisme();
    }
    
    public function getCurrentDefinitionDrm()
    {
    	if ($this->exist('definition_drm')) {
    		return array($this->getTypeNoeud() => $this->definition_drm);
    	}
    	return $this->callbackCurrentDefinitionDrm();
    }
    
    public function callbackCurrentDefinitionDrm()
    {
    	return $this->getParentNode()->getCurrentDefinitionDrm();
    }
    
    /*
     * Les fonctions ci-dessous sont relatives à la gestion de la configuration du catalogue produit
     */

    public function setDonneesCsv($datas) 
    {
    	$this->libelle = ($datas[$this->getCsvLibelle()])? $datas[$this->getCsvLibelle()] : null;
    	$this->code = ($datas[$this->getCsvCode()])? $datas[$this->getCsvCode()] : null;
    	if ($this->hasDepartements()) {
    		$departements = $this->getDepartementsCsv($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DEPARTEMENTS]);
    		if ($departements) {
    			$this->remove('departements');
    			$this->add('departements');
    			foreach ($departements as $departement) {
    				$this->departements->add(null, $departement);
    			}
    		}
    	}
    	if ($this->hasLabels()) {
    		$labels = $this->getLabelsCsv($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_LABELS]);
    		if ($labels) {
    			$this->remove('labels');
    			$this->add('labels');
    			foreach ($labels as $code => $libelle) {
    				$this->labels->add($code, $libelle);
    			}
    		}
    	}
    	if ($this->hasCvo()) {
    		$this->setDroitsCsv($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_CVO], ConfigurationProduit::NOEUD_DROIT_CVO, $this->getCodeApplicatif());
    	}
    	if ($this->hasDouane()) {
    		$this->setDroitsCsv($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_DOUANE], ConfigurationProduit::NOEUD_DROIT_DOUANE, $this->getCodeApplicatif());
    	}
    	if ($this->hasDRMVrac()) {
    		$this->drm_vrac = ($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DRM_VRAC])? 1 : 0;
    	}
    	if ($this->hasOIOC()) {
    		$this->setOIOCCsv($datas[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC]);
    	}
    	if ($this->hasDefinitionDrm()) {
    		$this->setDefinitionDRM(
    			$datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DRM_CONFIG_ENTREE_REPLI], 
    			$datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DRM_CONFIG_ENTREE_DECLASSEMENT],
    			$datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DRM_CONFIG_SORTIE_REPLI],
    			$datas[ConfigurationProduitCsvFile::CSV_PRODUIT_DRM_CONFIG_SORTIE_DECLASSEMENT]
    		);
    	}
    	$this->setDonneesCsvCallback($datas);
    }
    
    protected function setDonneesCsvCallback($datas)
    {
    	$this->getParentNode()->setDonneesCsv($datas);
    }
	
    protected function getDepartementsCsv($departements) 
    {
    	return ($departements)? explode(ConfigurationProduitCsvFile::CSV_DELIMITER_DEPARTEMENTS, $departements) : array();
    }
	
    protected function getLabelsCsv($labels) 
    {
    	$result = array();
    	if ($labels) {
    		$labels = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_LABELS, $labels);
    		foreach ($labels as $label) {
    			$label = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_LABELS_INTER, $label);
    			if (isset($label[0]) && isset($label[1])) {
    				$result[$label[0]] = $label[1];
    			}
    		}
    	}
    	return $result;
    }
	
    protected function setDefinitionDRM($entreeRepli, $entreeDeclassement, $sortieRepli, $sortieDeclassement) 
    {
    	$this->remove('definition_drm');
    	$this->add('definition_drm');
    	$this->definition_drm->entree->repli = ($entreeRepli)? 1 : 0;
    	$this->definition_drm->entree->declassement = ($entreeDeclassement)? 1 : 0;
    	$this->definition_drm->sortie->repli = ($sortieRepli)? 1 : 0;
    	$this->definition_drm->sortie->declassement = ($sortieDeclassement)? 1 : 0;
    }
    
    protected function setDroitsCsv($datas, $typeDroit, $noeud) 
    {
    	$droits = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_DROITS, $datas);
    	if ($droits) {
	    	$this->remove('droits');
	    	$this->add('droits');
	    	foreach ($droits as $droit) {
	    		$details = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_DROITS_INTER, $droit);
	    		$code = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_CODE]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_CODE])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_CODE] : null;
	    		$libelle = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_LIBELLE]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_LIBELLE])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_LIBELLE] : null;
	    		$taux = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_TAUX]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_TAUX])? $this->castFloat($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_TAUX]) : null;
	    		$date = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_DATE]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_DATE])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_DATE] : null;
	    		$n = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_NOEUD]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_NOEUD])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_DROITS_NOEUD] : null;
	    		if ($noeud == $n && $date) {
					$d = $this->droits->getOrAdd($typeDroit)->getOrAdd($date);
		    		$d->date = $date;
		    		$d->taux = $this->castFloat($taux);
		    		$d->code = $code;
		    		$d->libelle = $libelle;
	    		}
	    	}
    	}
    }
    
    protected function setOIOCCsv($datas) 
    {
    	$organismes = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_OIOC, $datas);
    	if ($organismes) {
	    	$this->remove('organismes');
	    	$this->add('organismes');
	    	foreach ($organismes as $organisme) {
	    		$details = explode(ConfigurationProduitCsvFile::CSV_DELIMITER_OIOC_INTER, $organisme);
	    		$date = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_DATE]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_DATE])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_DATE] : null;
	    		$oioc = (isset($details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_OIOC]) && $details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_OIOC])? $details[ConfigurationProduitCsvFile::CSV_PRODUIT_OIOC_OIOC] : null;
				if ($date) {
	    			$o = $this->organismes->getOrAdd($date);
		    		$o->date = $date;
		    		$o->oioc = $oioc;
				}
	    	}
    	}
    }
    
  	public abstract function hasDepartements();
  	public abstract function hasLabels();
 	public abstract function hasCvo();
 	public abstract function hasDouane();
  	public abstract function hasDRMVrac();
  	public abstract function hasOIOC();
  	public abstract function hasDefinitionDrm();
  	public abstract function getTypeNoeud();
  	public abstract function getCodeApplicatif();
  	public abstract function getCsvLibelle();
  	public abstract function getCsvCode();
}