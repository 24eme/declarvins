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

        return array($this->libelle);
    }

    protected function getCodesAbstract() {
      
        return $this->code;
    }


    public function getProduits($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro, '')->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsByCertification($this->getKey(), $interpro, $departement)->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[6].')';
            $produits[$item->key[5]] = $libelles;
        }

        ksort($produits);

        return $produits;
    }

    public function getProduitsLieux($interpro, $departement) {
        $produits = array();

        $results = ConfigurationClient::getInstance()->findProduitsLieuxByCertification($this->getKey(), $interpro, '')->rows;

        if ($departement) {
          $results = array_merge($results, ConfigurationClient::getInstance()->findProduitsLieuxByCertification($this->getKey(), $interpro, $departement)->rows);
        }

        foreach($results as $item) {
            $libelles = $item->value;
            unset($libelles[0]);
            $libelles[] = '('.$item->key[5].')';
            $produits[$item->key[4]] = $libelles;
        }

        ksort($produits);

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
  	
  	public function hasUniqProduit($interp) {
  		if ($interpros = $this->get('interpro')) {
  			if ($interpros->exist($interp)) {
  				if (count($interpros->get($interp)->labels) > 0) {
  					return false;
  				}
  			}
  		}
  		$produits = ConfigurationClient::getInstance()->findProduitsByCertificationAndInterpro($interp, $this->getKey());
  		if (count($produits->rows) == 1) {
  			foreach ($produits->rows as $produit) {
  				return $produit->key[7];
  			}
  		} else {
  			return false;
  		}  		
  	}
  	
  	public function hasProduit($interp) {
  		$produits = ConfigurationClient::getInstance()->findProduitsByCertificationAndInterpro($interp, $this->getKey());
  		if (count($produits->rows) == 0) {
  			return false;
  		} else {
  			return true;
  		}  		
  	}

}
