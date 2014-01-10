<?php
/**
 * Inheritance tree class _DAIDSTotal
 *
 */

abstract class _DAIDSTotal extends acCouchdbDocumentTree 
{
	abstract public function getChildrenNode();
	
    public function getProduits() 
    {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits = array_merge($produits, $item->getProduits());
        }

        return $produits;
    }
    
	public function getConfig() 
	{
        return ConfigurationClient::getCurrent()->getConfigurationProduit($this->getHash());
    }

    public function getParentNode() 
    {
        return $this->getParent()->getParent();
    }
    
    public function getLieuxArray() 
    {
        $lieux = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $lieux = array_merge($lieux, $item->getLieuxArray());
        }

        return $lieux;
    }

    public function nbComplete() 
    {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbComplete();
        }

        return $nb;
    }

    public function nbToComplete() 
    {
        $nb = 0;
        foreach($this->getChildrenNode() as $item) {
        	$nb += $item->nbToComplete();
        }

        return $nb;
    }

    public function isComplete() 
    {
        foreach($this->getChildrenNode() as $item) {
            if(!$item->isComplete()) {
                return false;
            }
        }

        return true;
    }
    
    protected function update($params = array()) 
    {
        parent::update($params);
    
        $this->total_manquants_excedents = $this->getTotalByKey('total_manquants_excedents');
        $this->total_pertes_autorisees = $this->getTotalByKey('total_pertes_autorisees');
        $this->total_manquants_taxables = $this->getTotalByKey('total_manquants_taxables');
        $this->total_douane = $this->getTotalByKey('total_douane');
        $this->total_cvo = $this->getTotalByKey('total_cvo');
        
        if ($this->exist('code') && $this->exist('libelle')) {
        	$this->code = $this->getFormattedCode();
        	$this->libelle = $this->getFormattedLibelle();
        }
		$this->selecteur = 1;
    }
    
    private function getTotalByKey($key) 
    {
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

    public function getFormattedLibelle($format = "%g% %a% %l% %co% %ce%") {
      return ConfigurationProduitClient::getInstance()->format($this->getConfig()->getLibelles(), array(), $format);
    }

   	public function getFormattedCode($format = "%g%%a%%l%%co%%ce%") {
      return ConfigurationProduitClient::getInstance()->format($this->getConfig()->getCodes(), array(), $format);
    }

    public function getPreviousSisterWithParent() 
    {
        $item = $this->getPreviousSister();
        $sister = null;
        if ($item) {
            $sister = $item;
        }
        if (!$sister) {
            $item = $this->getParentNode()->getPreviousSisterWithParent();
            if ($item) {
               $sister = $item->getChildrenNode()->getLast();
            }
        }
        return $sister; 
    }

    public function getNextSisterWithParent() 
    {
        $item = $this->getNextSister();
        $sister = null;
        if ($item) {
            $sister = $item;
        }
        if (!$sister) {
            $item = $this->getParentNode()->getNextSisterWithParent();
            if ($item) {
               $sister = $item->getChildrenNode()->getFirst();
            }
        }
        return $sister;
    }
}