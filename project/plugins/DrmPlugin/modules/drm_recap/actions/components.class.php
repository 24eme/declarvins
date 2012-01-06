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
        foreach($this->getUser()->getDrm()->produits->get($this->config_appellation->getLabel()->getKey()) as $appellation) {
        	foreach ($this->getUser()->getDrm()->produits->get($this->config_appellation->getLabel()->getKey())->get($appellation->getKey()) as $produit) {
	            if ($produit->stock_vide) {
	                continue;
	            }
	            if (!array_key_exists($produit->getAppellation()->getKey(), $this->appellations)) {
	                $this->appellations[$produit->getAppellation()->getKey()] = 0;
	                $this->appellations_updated[$produit->getAppellation()->getKey()] = 0;
	            }
	            
	            if (!$produit->pas_de_mouvement) {
	                $this->appellations[$produit->getAppellation()->getKey()] += 1;
		            foreach ($this->getUser()->getDrm()->declaration->certifications->get($this->config_appellation->getLabel()->getKey())->appellations->get($appellation->getKey())->couleurs as $couleur) {
		            	foreach ($couleur->cepages as $cepage) {
		            		foreach ($cepage->details as $detail) {
		            			if ($detail->total_entrees || $detail->total_sorties) {
		            				$this->appellations_updated[$produit->getAppellation()->getKey()] += 1;
		            			}
		            		}
		            	}
		            }
	            }
        	}
        }
        $this->appellation_keys = array_keys($this->appellations);
    }

}
