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
                foreach ($appellation->lieux as $lieu) {
                    foreach ($lieu->couleurs as $couleur) {
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
        }
        return $details;
    }
    
    public function initialiseCommeDrmSuivante() 
    {
    	foreach ($this->declaration->certifications as $certification) {
    		$certification->total_debut_mois = null;
    		$certification->total_entrees = null;
    		$certification->total_sorties = null;
    		$certification->total = null;
            foreach ($certification->appellations as $appellation) {
	    		$appellation->total_debut_mois = null;
	    		$appellation->total_entrees = null;
	    		$appellation->total_sorties = null;
	    		$appellation->total = null;
                foreach ($appellation->lieux as $lieu) {
		    		$lieu->total_debut_mois = null;
		    		$lieu->total_entrees = null;
		    		$lieu->total_sorties = null;
		    		$lieu->total = null;
                    foreach ($lieu->couleurs as $couleur) {
			    		$couleur->total_debut_mois = null;
			    		$couleur->total_entrees = null;
			    		$couleur->total_sorties = null;
			    		$couleur->total = null;
                    	foreach ($couleur->cepages as $cepage) {
				    		$cepage->total_debut_mois = null;
				    		$cepage->total_entrees = null;
				    		$cepage->total_sorties = null;
				    		$cepage->total = null;
    	                    foreach ($cepage->millesimes as $millesime) {
					    		$millesime->total_debut_mois = null;
					    		$millesime->total_entrees = null;
					    		$millesime->total_sorties = null;
					    		$millesime->total = null;
                                foreach ($millesime->details as $detail) {
						    		
                                	$detail->total_debut_mois = $detail->total;
						    		$detail->total_entrees = null;
						    		$detail->total_sorties = null;
						    		$detail->total = null;
						    		
        	                    	$detail->stocks_debut->bloque = $detail->stocks_fin->bloque;
        	                    	$detail->stocks_debut->warrante = $detail->stocks_fin->warrante;
        	                    	$detail->stocks_debut->instance = $detail->stocks_fin->instance;
        	                    	
        	                    	$detail->stocks_fin->bloque = null;
        	                    	$detail->stocks_fin->warrante = null;
        	                    	$detail->stocks_fin->instance = null;
        	                    	
        	                    	foreach ($detail->entrees as $key => $entree) {
        	                    		$detail->entrees->$key = null;
        	                    	}
        	                    	foreach ($detail->sorties as $key => $sortie) {
        	                    		$detail->sorties->$key = null;
        	                    	}
                                }
    	                    }
                    	}
                    }
                }
            }
        }
        $this->update();
    }

}