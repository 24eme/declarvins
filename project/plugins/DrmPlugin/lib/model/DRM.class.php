<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    public function constructId() {
        $this->set('_id', 'DRM-' . $this->identifiant . '-' . $this->campagne);
    }

    public function synchroniseProduits() {
        $details = array();
        foreach ($this->produits as $certification) {
            foreach ($certification as $appellation) {
            	foreach ($appellation as $item) {
	                if ($detail = $item->updateProduit()) {
	                    $details[] = $detail->getHash();
	                }
            	}
            }
        }
        foreach ($this->declaration->labels as $label) {
            foreach ($label->appellations as $appellation) {
                foreach ($appellation->couleurs as $couleur) {
                    foreach ($couleur->details as $detail) {
                        if (!in_array($detail->getHash(), $details)) {
                            $detail->updateProduit();
                        }
                    }
                }
            }
        }
    }

    public function synchroniseDeclaration() {
        foreach ($this->produits as $certification) {
            foreach ($certification as $appellation) {
            	foreach ($appellation as $item) {
                	$item->updateDetail();
            	}
            }
        }
    }

}