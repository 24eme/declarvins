<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

abstract class _DRMTotal extends acCouchdbDocumentTree {
    
	protected $_config = null;
	
    public function getConfig() {
		if (!$this->_config) {
			$this->_config = ConfigurationClient::getCurrent()->getConfigurationProduit($this->getHash());
		}
        return $this->_config;
    }

    public function getParentNode() {

        return $this->getParent()->getParent();
    }

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce%") {
    	$config = $this->getConfig();
    	if (!$config) {
    		return null;
    	}
      	return ConfigurationProduitClient::getInstance()->format($config->getLibelles(), array(), $format);
    }

   	public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") {
    	$config = $this->getConfig();
    	if (!$config) {
    		return null;
    	}
      return ConfigurationProduitClient::getInstance()->format($config->getCodes(), array(), $format);
    }

    protected function init($params = array()) {
        parent::init($params);
        $this->total_debut_mois = null;
        $this->total_entrees = null;
        $this->total_sorties = null;
        $this->total = null;
        $this->total_debut_mois_interpro = null;
        $this->total_entrees_interpro = null;
        $this->total_sorties_interpro = null;
        $this->total_interpro = null;
        $this->selecteur = 1;
		$this->total_entrees_nettes = null;
		$this->total_entrees_reciproque = null;
		$this->total_sorties_nettes = null;
		$this->total_sorties_reciproque = null;
    }
    
	protected function update($params = array()) {
        parent::update($params);
		$sumTotalDebutMois = 0;
		$sumTotalEntrees = 0;
		$sumTotalSorties = 0;
		$sumTotalEntreesNettes = 0;
		$sumTotalEntreesReciproque = 0;
		$sumTotalSortiesNettes = 0;
		$sumTotalSortiesReciproque = 0;
		$sumTotalDebutMoisInterpro = 0;
		$sumTotalEntreesInterpro = 0;
		$sumTotalSortiesInterpro = 0;
		$fields = $this->getFields();
    	foreach ($fields as $field => $k) {
    		if ($this->fieldIsCollection($field)) {
    			$noeud = $this->get($field);
    			foreach ($noeud as $f => $v) {
    				if ($noeud->fieldIsCollection($f)) {
    					if ($v->exist('total_debut_mois')) {
		    				$sumTotalDebutMois += $v->get('total_debut_mois');
    					}
    					if ($v->exist('total_entrees')) {
		    				$sumTotalEntrees += $v->get('total_entrees');
    					}
    					if ($v->exist('total_sorties')) {
		    				$sumTotalSorties += $v->get('total_sorties');
    					}
    					if ($v->exist('total_debut_mois_interpro')) {
		    				$sumTotalDebutMoisInterpro += $v->get('total_debut_mois_interpro');
    					}
    					if ($v->exist('total_entrees_interpro')) {
		    				$sumTotalEntreesInterpro += $v->get('total_entrees_interpro');
    					}
    					if ($v->exist('total_sorties_interpro')) {
		    				$sumTotalSortiesInterpro += $v->get('total_sorties_interpro');
    					}
    					if ($v->exist('total_entrees_nettes')) {
		    				$sumTotalEntreesNettes += $v->get('total_entrees_nettes');
    					}
    					if ($v->exist('total_entrees_reciproque')) {
		    				$sumTotalEntreesReciproque += $v->get('total_entrees_reciproque');
    					}
    					if ($v->exist('total_sorties_nettes')) {
		    				$sumTotalSortiesNettes += $v->get('total_sorties_nettes');
    					}
    					if ($v->exist('total_sorties_reciproque')) {
		    				$sumTotalSortiesReciproque += $v->get('total_sorties_reciproque');
    					}
    				}
    			}
    		}
    	}
        $this->total_debut_mois = round($sumTotalDebutMois, 2);
        $this->total_entrees = round($sumTotalEntrees, 2);
        $this->total_sorties = round($sumTotalSorties, 2);
        $this->total = round($sumTotalDebutMois + $sumTotalEntrees - $sumTotalSorties, 2);
        $this->total_debut_mois_interpro = round($sumTotalDebutMoisInterpro, 2);
        $this->total_entrees_interpro = round($sumTotalEntreesInterpro, 2);
        $this->total_sorties_interpro = round($sumTotalSortiesInterpro, 2);
        $this->total_interpro = round($sumTotalDebutMoisInterpro + $sumTotalEntreesInterpro - $sumTotalSortiesInterpro, 2);
        $this->total_entrees_nettes = round($sumTotalEntreesNettes, 2);
        $this->total_entrees_reciproque = round($sumTotalEntreesReciproque, 2);
        $this->total_sorties_nettes = round($sumTotalSortiesNettes, 2);
        $this->total_sorties_reciproque = round($sumTotalSortiesReciproque, 2);
        if ($this->exist('code') && $this->exist('libelle')) {
        	if (!$this->code) {
        		$this->code = $this->getFormattedCode();
        	}
        	if (!$this->libelle) {
        		$this->libelle = $this->getFormattedLibelle();
        	}
        }
        $this->selecteur = 1;
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->getFields() as $field => $k) {
    		if ($this->fieldIsCollection($field)) {
    			foreach ($this->get($field) as $f => $v) {
    				if ($this->get($field)->fieldIsCollection($f)) {
    					if ($v->exist($key)) {
		    				$sum += $v->get($key);
    					}
    				}
    			}
    		}
    	}
    	return $sum;
    }

    public function hasStockEpuise() {

        return $this->total_debut_mois == 0 && !$this->hasMouvement();
    }

    public function hasMouvement() {

        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }

    public function hasMouvementCheck() {
        foreach($this->getChildrenNode() as $item) {
            if($item->hasMouvementCheck()) {
                return true;
            }
        }

        return false;
    }

    public function nbComplete() {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbComplete();
        }

        return $nb;
    }

    public function nbToComplete() {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbToComplete();
        }

        return $nb;
    }

    public function isComplete() {
        foreach($this->getChildrenNode() as $item) {
            if(!$item->isComplete()) {
                return false;
            }
        }

        return true;
    }

    public function getPreviousSisterWithMouvementCheck() {
        $item = $this->getPreviousSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if (!$sister) {
            $item = $this->getParentNode()->getPreviousSisterWithMouvementCheck();
            if ($item) {
               
               $sister = $item->getChildrenNode()->getLast();
            }
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getPreviousSisterWithMouvementCheck();
        }

        return $sister; 
    }

    public function getNextSisterWithMouvementCheck() {
        $item = $this->getNextSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if (!$sister) {
            $item = $this->getParentNode()->getNextSisterWithMouvementCheck();
            if ($item) {
               
               $sister = $item->getChildrenNode()->getFirst();
            }
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getNextSisterWithMouvementCheck();
        }

        return $sister;
    }

    public function getProduits() {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits = array_merge($produits, $item->getProduits());
        }

        return $produits;
    }

    public function getLieuxArray() {
        $lieux = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $lieux = array_merge($lieux, $item->getLieuxArray());
        }

        return $lieux;
    }
  
    abstract public function getChildrenNode();
    
	public function getDroits() 
	{
		throw new sfException('Utilisé ?');
    	$droits = array();
    	if ($this->getConfig()->hasDroits()) {
      		foreach ($this->getConfig()->getDroits($this->getInterproKey()) as $key => $droit) {
				$droits[$key] = $droit->getCurrentDroit($this->getPeriode());
      		}
    	}
      	return $droits;
    }
    
    public function getInterproKey() 
    {
		if (!$this->getDocument()->getInterpro()) {
			return array();
		}
      	return $this->getDocument()->getInterpro()->get('_id');
    }
    
    public function getPeriode() 
    {
		return $this->getDocument()->getPeriode();
    }

}