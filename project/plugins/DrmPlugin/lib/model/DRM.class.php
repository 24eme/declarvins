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
    
    public function getNextCertification($currentCertification)
    {
    	$findCertification = false;
    	$nextCertification = null;
    	$config_certifications = ConfigurationClient::getCurrent()->declaration->certifications;
    	foreach ($config_certifications as $certification => $produit) {
            if ($this->produits->exist($certification)) {
            	if ($findCertification) {
            		$nextCertification = $this->declaration->certifications->get($certification);
            		break;
            	}
                if ($certification == $currentCertification->getKey()) {
                	$findCertification = true;
                }
            }
        }
        return $nextCertification;
    }

    public function setCampagneMoisAnnee($mois, $annee) {
      $this->campagne = sprintf("%04d-%02d", $annee, $mois);
    }

    public function setMois($mois) {
      $annee = $this->getAnnee();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function setAnnee($annee) {
      $mois = $this->getMois();
      $this->setCampagneMoisAnnee($mois, $annee);
    }

    public function getMois() {
      return preg_replace('/.*-/', '', $this->campagne)*1;
    }

    public function getAnnee() {
      return preg_replace('/-.*/', '', $this->campagne)*1;
    }
    
    public function setDroits() {
    	foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
            	$this->setDroit('douane', $appellation);
            	$this->setDroit('cvo', $appellation);
            	$appellation->calculDroits();
            }
    	}
    }
    
    private function setDroit($type, $appellation) {
    	$configurationDroits = $appellation->getConfig()->interpro->get($this->getInterpro()->get('_id'))->droits->get($type)->getCurrentDroit($this->campagne);
    	$droit = $appellation->droits->get($type);
    	$droit->ratio = $configurationDroits->ratio;
        $droit->code = $configurationDroits->code;
    }
    
    public function getEtablissement() {
    	return EtablissementClient::getInstance()->retrieveById($this->identifiant);
    }
    
    public function getInterpro() {
    	return $this->getEtablissement()->getInterproObject();
    }
    
    public function getTotalCvo() {
    	return $this->getTotalDroit('cvo');
    }
    public function getTotalDouane() {
    	return $this->getTotalDroit('douane');
    }    
    private function getTotalDroit($type) {
    	$total = 0;
    	foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
            	$total += $appellation->get('total_'.$type);
            }
    	}
    	return $total; 	
    }
    public function getTotalDroitByCode() {
    	$result = array();
    	foreach ($this->declaration->certifications as $certification) {
            foreach ($certification->appellations as $appellation) {
            	if (isset($result[$appellation->droits->get('douane')->code]['douane'])) {
            		$result[$appellation->droits->get('douane')->code]['douane'] += $appellation->get('total_douane');
            	} else {
            		$result[$appellation->droits->get('douane')->code]['douane'] = $appellation->get('total_douane');
            	}
            	if (isset($result[$appellation->droits->get('cvo')->code]['cvo'])) {
            		$result[$appellation->droits->get('cvo')->code]['cvo'] += $appellation->get('total_cvo');
            	} else {
            		$result[$appellation->droits->get('cvo')->code]['cvo'] = $appellation->get('total_cvo');
            	}
            }
    	}
    	return $result;
    }

    public function save($e = null) {
      if (!preg_match('/^2\d{3}-[01][0-9]$/', $this->campagne))
	throw new sfException('Wrong format for campagne ('.$this->campagne.')');
      return parent::save($e);
    }
}