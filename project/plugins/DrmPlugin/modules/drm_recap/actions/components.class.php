<?php

class drm_recapComponents extends sfComponents {

    public function executePopupAppellationAjout() {
        if (is_null($this->form)) {
            $this->form = new DRMAppellationAjoutForm($this->getUser()->getDrm()->declaration->certifications->add($this->label->getKey())->appellations);
        }
    }
    
    public function executeItemForm() {
        if (is_null($this->form) || $this->form->getObject()->getCouleur()->getKey() != $this->detail->getCouleur()->getKey() || $this->form->getObject()->getKey() != $this->detail->getKey()) {
            $this->form = new DRMDetailForm($this->detail);
        }
    }
    
    public function executeOnglets() {
        $this->appellations = array();
        $this->appellations_updated = array();
        $certification_key = $this->config_appellation->getCertification()->getKey();
        foreach($this->getUser()->getDrm()->produits->get($certification_key) as $appellation_key => $appellation) {
        	foreach ($appellation as $produit) {
	            if (!array_key_exists($produit->getAppellation()->getKey(), $this->appellations)) {
	                $this->appellations[$appellation_key] = 0;
	                $this->appellations_updated[$appellation_key] = 0;
	            }
	            if (!$produit->pas_de_mouvement && !$produit->stock_vide) {
	                $this->appellations[$appellation_key] += 1;
	                if ($produit->getDetail()->isComplete()) {
        				$this->appellations_updated[$appellation_key] += 1;
        			}
	            }
        	}
        }
        $this->appellation_keys = array_keys($this->appellations);
    }

}
