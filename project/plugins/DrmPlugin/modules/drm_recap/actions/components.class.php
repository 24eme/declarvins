<?php

class drm_recapComponents extends sfComponents {

    public function executeList() {
        $this->produits = $this->drm_appellation->getProduits();
    }
    
    public function executeItemForm() {
        if (is_null($this->form)) {
            $this->form = new DRMDetailForm($this->detail);
        }
    }
    
    public function executeOnglets() {
        $this->items = $this->drm_appellation->getParentNode()->getChildrenNode();

        /*$this->appellations = array();
        $this->appellations_updated = array();

        foreach($this->drm_appellation->getParentNode()->getChildrenNode() as $item) {
            if (!array_key_exists($item->getKey(), $this->appellations)) {
                $this->appellations[$appellation->getHash()] = 0;
                $this->appellations_updated[$appellation->getHash()] = 0;
            }
            if (!$produit->pas_de_mouvement) {
                $this->appellations[$appellation->getHash()] += 1;
                if ($produit->getDetail()->isComplete()) {
    				$this->appellations_updated[$appellation->getHash()] += 1;
    			}
            }
        }
        $this->appellation_keys = array_keys($this->appellations);*/
    }

}
