<?php

/**
 * Model for DRM
 *
 */
class DRM extends BaseDRM {

    const NOEUD_TEMPORAIRE = 'TMP';
    const DEFAULT_KEY = 'DEFAUT';
    const CSV_COL_TYPE = 0;
    const CSV_COL_DETAIL_IDENTIFIANT_DECLARANT = 1;
    const CSV_COL_DETAIL_NOM_DECLARANT = 2;
    const CSV_COL_DETAIL_ANNEE = 3;
    const CSV_COL_DETAIL_MOIS = 4;
    const CSV_COL_DETAIL_PRECEDENTE = 5;
    const CSV_COL_DETAIL_CERTIFICATION = 6;
    const CSV_COL_DETAIL_APPELLATION = 7;
    const CSV_COL_DETAIL_LIEU = 8;
    const CSV_COL_DETAIL_COULEUR = 9;
    const CSV_COL_DETAIL_CEPAGE = 10;
    const CSV_COL_DETAIL_MILLESIME = 11;
    const CSV_COL_DETAIL_LABELS = 12;
    const CSV_COL_DETAIL_MENTION = 13;
    const CSV_COL_DETAIL_TOTAL_DEBUT_MOIS = 14;
    const CSV_COL_DETAIL_ENTREES = 15;
    const CSV_COL_DETAIL_ENTREES_NOUVEAU = 16;
    const CSV_COL_DETAIL_ENTREES_REPLI = 17;
    const CSV_COL_DETAIL_ENTREES_DECLASSEMENT = 18;
    const CSV_COL_DETAIL_ENTREES_MOUVEMENT = 19;
    const CSV_COL_DETAIL_ENTREES_CRD = 20;
    const CSV_COL_DETAIL_SORTIES = 21;
    const CSV_COL_DETAIL_SORTIES_VRAC = 22;
    const CSV_COL_DETAIL_SORTIES_EXPORT = 23;
    const CSV_COL_DETAIL_SORTIES_FACTURES = 24;
    const CSV_COL_DETAIL_SORTIES_CRD = 25;
    const CSV_COL_DETAIL_SORTIES_CONSOMMATION = 26;
    const CSV_COL_DETAIL_SORTIES_PERTES = 27;
    const CSV_COL_DETAIL_SORTIES_DECLASSEMENT = 28;
    const CSV_COL_DETAIL_SORTIES_REPLI = 29;
    const CSV_COL_DETAIL_SORTIES_MOUVEMENT = 30;
    const CSV_COL_DETAIL_SORTIES_LIES = 31;
    const CSV_COL_DETAIL_TOTAL = 32;
    const CSV_COL_DETAIL_STOCKFIN_BLOQUE = 33;
    const CSV_COL_DETAIL_STOCKFIN_WARRANTE = 34;
    const CSV_COL_DETAIL_STOCKFIN_INSTANCE = 35;

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

    private function interpretHash($hash) {
      if (!preg_match('|declaration/certifications/([^/]*)/appellations/([^/]*)/|', $hash, $match)) {
	throw new sfException($hash." invalid");
      }
      return array('certification' => $match[1], 'appellation' => $match[2]);
    }

    public function getProduit($hash, $labels = array()) {
      $hashes = $this->interpretHash($hash);
      sort($labels);
      try {
	if ($produits = $this->getProduits()->get($hashes['certification'])->get($hashes['appellation'])) {
	  foreach ($produits as $p) {
	    $leslabels = $p->label->toArray();
	    sort($leslabels);
	    if (!count(array_diff($leslabels,$labels)) &&  $p->hashref == $hash) {
	      return $p;
	    }
	  }
	}
      }catch(Exception $e) {
      }
      return false;
    }

    public function addProduit($hash, $labels = array()) {
      if ($p = $this->getProduit($hash, $labels)) {
	return $p;
      }
      $hashes = $this->interpretHash($hash);
      $produit = $this->produits->add($hashes['certification'])->add($hashes['appellation'])->add();
      $produit->setLabel($labels);
      $produit->setHashref($hash);

      $this->synchroniseDeclaration();
      
      return $produit;

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