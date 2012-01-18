<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

    protected $_couleur = null;
    protected $_cepage = null;
    
    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
        return $this->getCepage()->getCouleur();
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
    public function getAppellation() {
        return $this->getCouleur()->getAppellation();
    }

    public function updateProduit($produit = null) {
        if (is_null($produit)) {
            $produit = $this->getDocument()->produits->add($this->getAppellation()->getCertification()->getKey())->add($this->getAppellation()->getKey())->add();
        }
        $produit->label = $this->label;
        $produit->label_supplementaire = $this->label_supplementaire;
        $produit->couleur = $this->getCouleurValue();
        $produit->cepage = $this->getCepageValue();
        
        return $this;
    }
    
    public function getCouleurValue() {
        if (is_null($this->_couleur)) {
            return $this->getCouleur()->getKey();
        }
        
        return $this->_couleur;
    }
    
    public function getCepageValue() {
        if (is_null($this->_cepage)) {
            return $this->getCepage()->getKey();
        }
        
        return $this->_cepage;
    }
    
    public function setCouleurValue($value) {
       $this->_couleur = $value;
    }
    
    public function setCepageValue($value) {
       $this->_cepage = $value;
    }
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode('-', $this->label->toArray());
    	}
    	return ($key)? $key : DRMProduit::DEFAULT_KEY;
    }

    public function isNew() {
        return $this->getKey() == DRMProduit::DEFAULT_KEY;
    }
    
    protected function update($params = array()) {
        parent::update($params);
        $this->set('total_entrees', $this->getTotalByKey('entrees'));
        $this->set('total_sorties', $this->getTotalByKey('sorties'));
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->get($key) as $k) {
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

    public function isComplete() {
        return $this->total_entrees > 0 || $this->total_sorties > 0;
    }



}