<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {
	
	const LABEL_DEFAULT_KEY = 'defaut';

    protected $_couleur = null;
    
    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
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
            $produit = $this->getDocument()->produits->add($this->getAppellation()->getLabel()->getKey())->add();
        }
        $produit->label = $this->label;
        $produit->label_supplementaire = $this->label_supplementaire;
        $produit->couleur = $this->getCouleurValue();
        $produit->appellation = $this->getAppellation()->getKey();
        
        return $this;
    }
    
    public function getCouleurValue() {
        if (is_null($this->_couleur)) {
            return $this->getCouleur()->getKey();
        }
        
        return $this->_couleur;
    }
    
    public function setCouleurValue($value) {
       $this->_couleur = $value;
    }
    
    public function getLabelKey() {
    	$key = '';
    	if ($this->label) {
    		$key .= implode('-', $this->label->toArray());
    	}
    	if ($this->label_supplementaire) {
    		if ($key) {
    			$key .= '-';
    		}
    		$key .= $this->label_supplementaire;
    	} 
    	return ($key)? $key : self::LABEL_DEFAULT_KEY;
    }

}