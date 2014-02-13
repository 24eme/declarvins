<?php
abstract class _ConfigurationProduit extends acCouchdbDocumentTree 
{
	protected $libelles = null;
	protected $codes = null;
	protected $produits = array();
	protected $tree_produits = array();
	protected $all_libelles = array();
	protected $total_lieux = array();
	
    public function loadAllData() 
    {
		parent::loadAllData();
		$this->getProduits();
		$this->getTreeProduits();
		$this->getAllCepages();
		$this->getTotalLieux();
		$this->getCodes();
		$this->getAllAppellations();
		$this->getAllCertifications();
		$this->getAllLabels();
		$this->getAllLieux();
		$this->getAllCepages();
		$this->getAllDepartements();
    }
    
  	public function getParentNode() 
  	{
		$parent = $this->getParent()->getParent();
		if ($parent->getKey() == 'declaration') {
			throw new sfException('Noeud racine atteint');
		} else {
			return $this->getParent()->getParent();
		}
	}
	
	public function getProduits($departements = null, $onlyForDrmVrac = false, $cvoNeg = false, $date = null) 
	{   
		$key = sprintf("%s_%s_%s_%s", is_array($departements) ? implode('_', $departements) : $departements, $onlyForDrmVrac, $cvoNeg, $date);
		if(!array_key_exists($key, $this->produits)) {
			$produits = array();
	      	foreach($this->getChildrenNode() as $key => $item) {
	        	$produits = array_merge($produits, $item->getProduits($departements, $onlyForDrmVrac, $cvoNeg, $date));
	      	}
	      	$this->produits[$key] = $produits;
		}
        return $this->produits[$key];
  	}
        
    public function getTreeProduits()
    {
    	$key = sprintf("%s", 'all');
		if(!array_key_exists($key, $this->tree_produits)) {
			$client = ConfigurationProduitClient::getInstance();
			$produits = ($this->getKey() == ConfigurationProduit::DEFAULT_KEY || $this->getTypeNoeud() == 'declaration')? array() : array($this->getHash() => $client->format($this->getLibelles(), array(), "%c% %g% %a% %m% %l% %co% %ce%"));
	      	foreach($this->getChildrenNode() as $key => $item) {
	        	$produits = array_merge($produits, $item->getTreeProduits());
	      	}
	      	$this->tree_produits[$key] = $produits;
		}
		return $this->tree_produits[$key];
    }
	
	public function getTotalLieux($departements = null) 
	{
		$key = sprintf("%s", is_array($departements) ? implode('_', $departements) : $departements);
		if(!array_key_exists($key, $this->total_lieux)) {
	      	$lieux = array();
	      	foreach($this->getChildrenNode() as $key => $item) {
	        	$lieux = array_merge($lieux, $item->getTotalLieux($departements));
	      	}
	    	$this->total_lieux[$key] = $lieux;
		}
		return $this->total_lieux[$key];
  	}
  	
  	
  	public function hasProduits($departements = null, $onlyForDrmVrac = false)
  	{
  		return (count($this->getProduits($departements, $onlyForDrmVrac)) > 0)? true : false; 
  	}
  	
  	public function hasLieux($departements = null)
  	{
  		return (count($this->getTotalLieux($departements)) > 0)? true : false; 
  	}
    
    public function getAllAppellations()
    {
    	return $this->getAllAbstract("getAllAppellations");
    }
    
    public function getAllCertifications()
    {
    	return $this->getAllAbstract("getAllCertifications");
    }
    
    public function getAllLabels()
    {
    	return $this->getAllAbstract("getAllLabels");
    }
    
    public function getAllLieux()
    {
    	return $this->getAllAbstract("getAllLieux");
    }
    
    public function getAllCepages()
    {
    	return $this->getAllAbstract("getAllCepages");
    }
    
    public function getAllDepartements()
    {
    	return $this->getAllAbstract("getAllDepartements");
    }
    
    public function getAllAbstract($function) {
    	if(!array_key_exists($function, $this->all_libelles)) {
	    	$items = array();
	      	foreach($this->getChildrenNode() as $key => $item) {
	      		$result = $item->$function();
	      		if (is_array($result)) {
	        		$items = array_merge($items, $result);
	      		}
	      	}
	      	$this->all_libelles[$function] = $items;
    	}
    	return $this->all_libelles[$function];
    }
    
	public function getLibelles() 
	{
    	if(is_null($this->libelles)) {
        	$this->libelles = array_merge($this->getParentNode()->getLibelles(), array($this->libelle));
        }
        return $this->libelles;
    }

    public function getCodes() 
    {
		if(is_null($this->codes)) {
			$this->codes = array_merge($this->getParentNode()->getCodes(), array($this->code));
		}
		return $this->codes;
    }
    
    protected function castFloat($float) 
    {
    	return floatval(str_replace(',', '.', $float));
    }
    
    public abstract function getChildrenNode();
    

    /*
     * Les fonctions ci-dessous permettent la récupération de la configuration d'un produit
     */
    public function getCurrentDroit($typeDroit, $atDate = null, $onlyValue = false)
    {
    	$atDate = ($atDate)? $atDate : date('Y-m-d');
    	if ($this->exist('droits')) {
    		$droits = $this->droits->get($typeDroit)->toArray();
    		if (count($droits) > 0) {
    			ksort($droits);
    			$d = null;
    			foreach ($droits as $date => $droit) {
    				if ($date <= $atDate) {
    					$d = $droit;
    				}
    			}
    			if ($d) {
    				return ($onlyValue)? $d : array($this->getTypeNoeud() => $d);
    			}
    		}
    	}
    	return $this->callbackCurrentDroit($typeDroit, $atDate, $onlyValue);
    }
    
    public function callbackCurrentDroit($typeDroit, $atDate = null, $onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentDroit($typeDroit, $atDate, $onlyValue);
    }
    
	public function getHistoryDroit($typeDroit, $onlyValue = false)
    {
    	$result = array();
    	if ($this->exist('droits')) {
    		$droits = $this->droits->get($typeDroit)->toArray();
    		if (count($droits) > 0) {
    			$result = ($onlyValue)? $droits : array($this->getCodeApplicatif() => $droits);
    		}
    	}
    	return array_merge($this->callbackHistoryDroit($typeDroit, $onlyValue), $result);
    }
    
    public function callbackHistoryDroit($typeDroit, $onlyValue = false)
    {
    	return $this->getParentNode()->getHistoryDroit($typeDroit, $onlyValue);
    }
    
    public function getCurrentDepartements($onlyValue = false)
    {
    	if ($this->exist('departements')) {
    		$departements = $this->departements->toArray();
    		if (count($departements) > 0) {
    			return ($onlyValue)? $departements : array($this->getTypeNoeud() => $departements);
    		}
    	}
    	return $this->callbackCurrentDepartements($onlyValue);
    }
    
    public function callbackCurrentDepartements($onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentDepartements($onlyValue);
    }
    
    public function getCurrentLabels($onlyValue = false)
    {
    	if ($this->exist('labels')) {
    		$labels = $this->labels->toArray();
    		if (count($labels) > 0) {
    			return ($onlyValue)? $labels : array($this->getTypeNoeud() => $labels);
    		}
    	}
    	return $this->callbackCurrentLabels($onlyValue);
    }
    
    public function callbackCurrentLabels($onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentLabels($onlyValue);
    }
    
    public function getCurrentDrmVrac($onlyValue = false)
    {
    	if ($this->exist('drm_vrac')) {
    		$drm_vrac = ($this->drm_vrac)? 1 : 0;
    		return ($onlyValue)? $drm_vrac : array($this->getTypeNoeud() => $drm_vrac);
    	}
    	return $this->callbackCurrentDrmVrac($onlyValue);
    }
    
    public function callbackCurrentDrmVrac($onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentDrmVrac($onlyValue);
    }
    
    public function getCurrentOrganisme($atDate = null, $onlyValue = false)
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
    				return ($onlyValue)? $o : array($this->getTypeNoeud() => $o);
    			}
    		}
    	}
    	return $this->callbackCurrentOrganisme($atDate, $onlyValue);
    }
    
    public function callbackCurrentOrganisme($atDate = null, $onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentOrganisme($atDate, $onlyValue);
    }
    
	public function getHistoryOrganisme($onlyValue = false)
    {
    	$result = array();
    	if ($this->exist('organismes')) {
    		$organismes = $this->organismes->toArray();
    		if (count($organismes) > 0) {
    			$result = ($onlyValue)? $organismes : array($this->getCodeApplicatif() => $organismes);
    		}
    	}
    	return array_merge($this->callbackHistoryOrganisme($onlyValue), $result);
    }
    
    public function callbackHistoryOrganisme($onlyValue = false)
    {
    	return $this->getParentNode()->getHistoryOrganisme($onlyValue);
    }
    
    public function getCurrentDefinitionDrm($onlyValue = false)
    {
    	if ($this->exist('definition_drm')) {
    		return ($onlyValue)? $this->definition_drm : array($this->getTypeNoeud() => $this->definition_drm);
    	}
    	return $this->callbackCurrentDefinitionDrm($onlyValue);
    }
    
    public function callbackCurrentDefinitionDrm($onlyValue = false)
    {
    	return $this->getParentNode()->getCurrentDefinitionDrm($onlyValue);
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