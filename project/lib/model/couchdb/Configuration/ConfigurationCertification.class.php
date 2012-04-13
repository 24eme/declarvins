<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification {
	
	const TYPE_NOEUD = 'certification';

    
    protected function loadAllData() {
        parent::loadAllData();
    }

    protected function getLibellesAbstract() {

        return array($this->getKey() => $this->libelle);
    }

    public function getProduits($interpro, $departement = null) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[6].')';
            $produits[$item->key[5]] = $libelles;
        }

        return $produits;
    }

    public function getProduitsAppellations($interpro, $departement = null) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsAppellationsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[5].')';
            $produits[$item->key[4]] = $libelles;
        }

        return $produits;
    }

    public function getLabels($interpro) {
        $labels = array();
        $results = ConfigurationClient::getInstance()->findLabelsByCertification($this->getKey(), $interpro);
        foreach($results->rows as $item) {
            $labels[$item->key[3]] = $item->value;
        }

        return $labels;
    }

    public function getDroits($interpro) {
      return $this->interpro->getOrAdd($interpro)->droits;
    }
    
    public function setLabelCsv($datas) {
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
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE] : null;
    	if (ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_NOEUD]) {
    		$this->setDroitDouaneCsv($datas);
    	}
    	if (ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT == $datas[ProduitCsvFile::CSV_PRODUIT_CVO_NOEUD]) {
    		$this->setDroitCvoCsv($datas);
    	}
    }
    
    private function setDroitDouaneCsv($datas) {
    	$droits = $this->getDroits('INTERPRO-'.strtolower($datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]));
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_DOUANE_DATE] : date('Y-m-d');
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
    	$date = ($datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE])? $datas[ProduitCsvFile::CSV_PRODUIT_CVO_DATE] : date('Y-m-d');
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
  	public function hasLabel() {
  		return true;
  	}
	
  	public function getTypeNoeud() {
  		return self::TYPE_NOEUD;
  	}
}