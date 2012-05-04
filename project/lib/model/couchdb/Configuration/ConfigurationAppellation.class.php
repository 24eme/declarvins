<?php
/**
 * Model for ConfigurationAppellation
 *
 */

class ConfigurationAppellation extends BaseConfigurationAppellation {
	
	const TYPE_NOEUD = 'appellation';
    
    protected function loadAllData() {
        parent::loadAllData();
        $this->hasCepage();
        $this->hasMillesime();
    }

    public function getCertification() {
        return $this->getParentNode();
    }

    public function getCepagesChoices(array $exclude_key = array())
    {
        $choices = array();
        foreach ($this->couleurs as $couleur_key => $couleur) {
            foreach ($couleur->cepages as $cepage_key => $cepage) {
                if (!in_array($cepage_key, $exclude_key)) {
                    $choices[$couleur_key][$cepage_key] = $cepage->getLibelle();
                }
            }
        }
        
        return $choices;    
    }

    public function hasCepage() {
        return $this->store('has_cepage', array($this, 'hasCepageStore'));
    }

    public function hasMillesime() {
        return $this->store('has_millesime', array($this, 'hasMillesimeStore'));
    }

    public function hasCepageStore() {
        foreach($this->lieux as $lieu) {
            foreach($lieu->couleurs as $couleur) {
                if ($couleur->hasCepage()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasMillesimeStore() {
        foreach($this->lieux as $lieu) {
            foreach($lieu->couleurs as $couleur) {
                if ($couleur->hasMillesime()) {
                    return true;
                }
            }
        }

        return false;
    }

    public function getProduits($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByAppellation($this->getCertification()->getKey(), $interpro, '', $this->getKey())->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsByAppellation($this->getCertification()->getKey(), $interpro, $departement, $this->getKey())->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            unset($libelles[1]);
            $libelles[] = '('.$item->key[6].')';
            $produits[$item->key[5]] = $libelles;
        }

        ksort($produits);

        return $produits;
    }

    public function getLibelle() {
      $libelle = $this->_get('libelle');
      if ($libelle)
	return $libelle;
      return 'Total';
    }

    public function getDroits($interpro) {
      return $this->interpro->getOrAdd($interpro)->droits;
    }
    
    public function setLabelCsv($datas) {
    	$this->getCertification()->setLabelCsv($datas);
    	$labels = $this->interpro->getOrAdd('INTERPRO-'.strtolower($datas[LabelCsvFile::CSV_LABEL_INTERPRO]))->labels;
    	$canInsert = true;
    	foreach ($labels as $label) {
    		if ($label == $datas[LabelCsvFile::CSV_LABEL_CODE]) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$labels->add(null, $datas[LabelCsvFile::CSV_LABEL_CODE]);
    	}
    }
    
    public function setDonneesCsv($datas) {
    	$this->getCertification()->setDonneesCsv($datas);
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE] : null;
    	if (ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD]) {
    		$this->setDroitDouaneCsv($datas);
    	}
    	if (ProduitCsvFile::CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {
    		$this->setDroitCvoCsv($datas);
    	}
    }
    
    private function setDroitDouaneCsv($datas) {
    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_TAXE]) : null;
    	$code = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_CODE] : null;
    	$canInsert = true;
    	foreach ($droits->douane as $droit) {
    		if ($droit->date == $date && $droit->taux == $taux && $droit->code == $code) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$droits = $droits->douane->add();
	    	$droits->date = $date;
	    	$droits->taux = $taux;
	    	$droits->code = $code;
    	}
    }
    
    private function setDroitCvoCsv($datas) {
    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : '1900-01-01';
    	$taux = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE])? $this->castFloat($datas[ProduitCsvFile::CSV_PRODUIT_CVO_TAXE]) : null;
    	$code = ConfigurationDroits::CODE_CVO;
    	$canInsert = true;
    	foreach ($droits->cvo as $droit) {
    		if ($droit->date == $date && $droit->taux == $taux && $droit->code == $code) {
    			$canInsert = false;
    			break;
    		}
    	}
    	if ($canInsert) {
	    	$droits = $droits->cvo->add();
	    	$droits->date = $date;
	    	$droits->taux = $taux;
	    	$droits->code = $code;
    	}
    }
    
    private function castFloat($float) {
    	return floatval(str_replace(',', '.', $float));
    }
    
  	public function hasDepartements() {
  		return true;
  	}
  	public function hasDroits() {
  		return true;
  	}
  	public function hasLabels() {
  		return true;
  	}
  	public function hasDetails() {
  		return true;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
  	
  	public function getDetailConfiguration() {
  		$details = $this->getCertification()->getDetailConfiguration();
  		if ($this->exist('detail')) {
  			foreach ($this->detail as $type => $detail) {
  				foreach ($detail as $noeud => $droits) {
  					if ($droits->readable !== null)
  						$details->get($type)->get($noeud)->readable = $droits->readable;
  					if ($droits->writable !== null)
  						$details->get($type)->get($noeud)->writable = $droits->writable;
  				}
  			}
  		}
  		return $details;
  	}
    
}
