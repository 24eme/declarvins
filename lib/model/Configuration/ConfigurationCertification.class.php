<?php
/**
 * Model for ConfigurationCertification
 *
 */

class ConfigurationCertification extends BaseConfigurationCertification 
{
	
	const TYPE_NOEUD = 'certification';
    
    protected function loadAllData() 
    {
        parent::loadAllData();
    }

    public function getLibelles() 
    {
        return array($this->libelle);
    }

    public function getCodes() 
    {
        return array($this->code);
    }

    public function setDonneesCsv($datas) 
    {
    	$this->libelle = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_LIBELLE] : null;
    	$this->code = ($datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE])? $datas[ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE] : null;
		$this->interpro->getOrAdd('INTERPRO-'.$datas[ProduitCsvFile::CSV_PRODUIT_INTERPRO]);
    	$this->setDroitDouaneCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
    	$this->setDroitCvoCsv($datas, ProduitCsvFile::CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT);
    }
    
  	public function hasDepartements() 
  	{
  		return true;
  	}
  	
  	public function hasDroits() 
  	{
  		return true;
  	}
  	
  	public function hasLabels() 
  	{
  		return true;
  	}
  	
  	public function hasDetails() 
  	{
  		return true;
  	}
	
  	public function getTypeNoeud() 
  	{
  		return self::TYPE_NOEUD;
  	}


    public function getProduitsByDepartement($departement) 
    {
        $produits = ConfigurationProduitsView::getInstance()->findProduitsByCertificationAndDepartement($this->getKey(), '')->rows;
        if ($departement) {
        	if (!is_array($departement)) {
        		$departement = array($departement);
        	}
        	foreach ($departement as $dep) {
        		$produits = array_merge($produits, ConfigurationProduitsView::getInstance()->findProduitsByCertificationAndDepartement($this->getKey(), $dep)->rows);
        	}
        }
        return $produits;
    }

    public function formatProduits($departement, $format = "%g% %a% %l% %co% %ce%") 
    {
    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduitsByDepartement($departement), $format);
    }

    public function getProduitsLieux($departement) 
    {
        $produits = ConfigurationProduitsView::getInstance()->findLieuxByCertificationAndDepartement($this->getKey(), '')->rows;
        if ($departement) {
        	if (!is_array($departement)) {
        		$departement = array($departement);
        	}
        	foreach ($departement as $dep) {
          		$produits = array_merge($produits, ConfigurationProduitsView::getInstance()->findLieuxByCertificationAndDepartement($this->getKey(), $dep)->rows);
        	}
        }
        return $produits;
    }

    public function formatProduitsLieux($departement, $format = "%g% %a% %l% %co% %ce%") 
    {
    	return ConfigurationProduitsView::getInstance()->formatProduits($this->getProduitsLieux($departement), $format);
    }
  	
  	public function hasProduit($departement) 
  	{
        if (!is_array($departement)) {
        	$departement = array($departement);
        }
  		$total = 0;
        foreach ($departement as $dep) {
        	$produits = ConfigurationProduitsView::getInstance()->nbProduitsByCertificationAndDepartement($this->getKey(), $dep);
	        if (isset($produits->rows[0]) && $produits->rows[0]->value > 0) {
	  			$total += $produits->rows[0]->value;
	  		}
        }
  		$produitsDefaut = ConfigurationProduitsView::getInstance()->nbProduitsByCertificationAndDepartement($this->getKey(), '');
  		if (isset($produitsDefaut->rows[0]) && $produitsDefaut->rows[0]->value > 0) {
  			$total += $produitsDefaut->rows[0]->value;
  		}
  		return ($total > 0);
  	}
  	
	public function getLabels($interpro) 
	{
        $labels = array();
        $results = ConfigurationProduitsView::getInstance()->findLabelsByCertification($interpro, $this->getKey());
        foreach($results->rows as $item) {
            $labels[$item->key[5]] = $item->value;
        }

        return $labels;
    }

}
