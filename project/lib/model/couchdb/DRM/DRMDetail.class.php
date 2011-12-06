<?php

/**
 * Model for DRMDetail
 *
 */
class DRMDetail extends BaseDRMDetail {

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

        $produit->denomination = $this->denomination;
        $produit->label = $this->label;
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

}