<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';

    public function constructId() {
        $this->set('_id', 'DRM-' . $this->identifiant . '-' . $this->campagne);
    }

    /*public function synchroniseProduits() {
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
        foreach ($this->declaration->certifications as $certifications) {
            foreach ($certifications->appellations as $appellation) {
                foreach ($appellation->couleurs as $couleur) {
                	foreach ($couleur->cepages as $cepage) {
	                    foreach ($cepage->millesimes as $millesime) {
                            foreach ($millesime->details as $detail) {
	                           if (!in_array($detail->getHash(), $details)) {
                                    $detail->updateProduit();
	                           }
                            }
	                    }
                	}
                }
            }
        }
    }*/

    public function synchroniseDeclaration() {
        foreach ($this->produits as $certification) {
            foreach ($certification as $appellation) {
            	foreach ($appellation as $item) {
                	$item->updateDetail();
            	}
            }
        }
    }
    
    public function getDetailsAvecVrac() {
        $details = array();
        foreach ($this->declaration->certifications as $certifications) {
            foreach ($certifications->appellations as $appellation) {
                foreach ($appellation->couleurs as $couleur) {
                	foreach ($couleur->cepages as $cepage) {
	                    foreach ($cepage->millesimes as $millesime) {
                            foreach ($millesime->details as $detail) {
    	                        if ($detail->sorties->vrac) {
    	                            $details[] = $detail;
    	                        }
                            }
	                    }
                	}
                }
            }
        }
        return $details;
    }

}