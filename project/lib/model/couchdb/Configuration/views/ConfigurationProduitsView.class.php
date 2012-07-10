<?php

class ConfigurationProduitsView extends acCouchdbView
{
	const KEY_TYPE_LINE = 0;
	const KEY_INTERPRO_ID = 1;
	const KEY_CERTIFICATION_KEY = 2;
	const KEY_DEPARTEMENT = 3;
	const KEY_LIEU_HASH = 4;
	const KEY_HASH = 5;
	const KEY_CODE = 6;

	const VALUE_LIBELLE_CERTIFICATION = 0;
	const VALUE_LIBELLE_GENRE = 1;
	const VALUE_LIBELLE_APPELLATION = 2;
	const VALUE_LIBELLE_MENTION = 3;
	const VALUE_LIBELLE_LIEU = 4;
	const VALUE_LIBELLE_COULEUR = 5;
	const VALUE_LIBELLE_CEPAGE = 6;

	const VALUE_CODE_CERTIFICATION = 0;
	const VALUE_CODE_GENRE = 1;
	const VALUE_CODE_APPELLATION = 2;
	const VALUE_CODE_MENTION = 3;
	const VALUE_CODE_LIEU = 4;
	const VALUE_CODE_COULEUR = 5;
	const VALUE_CODE_CEPAGE = 6;

	const TYPE_LINE_PRODUITS = 'produits';
	const TYPE_LINE_LIEUX = 'lieux';
	const TYPE_LINE_LABELS = 'labels';

	public static function getInstance() {

        return acCouchdbManager::getView('configuration', 'produits', 'Configuration');
    }

    public function findProduits() {

    	return $this->client->startkey(array(self::TYPE_LINE_PRODUITS))
              				->endkey(array(self::TYPE_LINE_PRODUITS, array()))
              				->reduce(false)
              				->getView($this->design, $this->view);
  	}

  	public function findProduitsByCertification($interpro, $certification, $departement) {
		
		return $this->client->startkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement))
              				->endkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement, array()))
              				->reduce(false)
              				->getView($this->design, $this->view);
  	}

  	public function nbProduitsByCertification($interpro, $certification, $departement) {
		
		return $this->client->startkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement))
	            			->endkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement, array()))
					    	->reduce(true)
					    	->group_level(4)
              				->getView($this->design, $this->view);
	}

  	public function findProduitsByLieu($interpro, $certification, $departement, $lieu_hash) {

	    if (substr($lieu_hash, 0,1) == "/") {
	      $lieu_hash = substr($lieu_hash, 1,strlen($lieu_hash)-1);
	    }

    	return $this->client->startkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement, $lieu_hash))
              				->endkey(array(self::TYPE_LINE_PRODUITS, $interpro, $certification, $departement, $lieu_hash, array()))
              				->reduce(false)
							->getView($this->design, $this->view);  
	}

  	public function findLieuxByCertification($interpro, $certification, $departement) {

		return $this->client->startkey(array(self::TYPE_LINE_LIEUX, $interpro, $certification, $departement))
              				->endkey(array(self::TYPE_LINE_LIEUX, $interpro, $certification, $departement, array()))
              				->reduce(false)
              				->getView($this->design, $this->view);
  	}


  	public function findLabelsByCertification($interpro, $certification) {
    	
    	return $this->client->startkey(array(self::TYPE_LINE_LABELS, $interpro, $certification))
              				->endkey(array(self::TYPE_LINE_LABELS, $interpro, $certification, array()))
              				->reduce(false)
              				->getView($this->design, $this->view);
  	}

  	public function getProduitsLibelles() {
  		$produits = $this->findProduits();
    	$libelles = array();

    	foreach($produits->rows as $item) {
    		$libelles['/'.$item->key[self::KEY_HASH]] = $item->value->libelles;
    	}

    	return $libelles;
  	}

  	public function getProduitsCodes() {
  		$produits = $this->findProduits();
    	$codes = array();

    	foreach($produits->rows as $item) {
    		$codes['/'.$item->key[self::KEY_HASH]] = $item->value->codes;
    	}

    	return $codes;
  	}

  	public function formatLibelles($libelles, $format = "%g% %a% %l% %co% %ce%") {
		$format_index = array('%c%' => self::VALUE_LIBELLE_CERTIFICATION,
		                      '%g%' => self::VALUE_LIBELLE_GENRE,
		                      '%a%' => self::VALUE_LIBELLE_APPELLATION,
		                      '%m%' => self::VALUE_LIBELLE_MENTION,
		                      '%l%' => self::VALUE_LIBELLE_LIEU,
		                      '%co%' => self::VALUE_LIBELLE_COULEUR,
		                      '%ce%' => self::VALUE_LIBELLE_CEPAGE);

		$libelle = $format;

		foreach($format_index as $key => $item) {
		  if (isset($libelles[$item])) {
		    $libelle = trim(str_replace($key, $libelles[$item], $libelle));
		  } else {
		    $libelle = trim(str_replace($key, "", $libelle));
		  }
		}

		return $libelle;
  	}

  	public function formatCodes($codes, $format = "%g%%a%%l%%co%%ce%") {
		$format_index = array('%c%' => self::VALUE_CODE_CERTIFICATION,
		                      '%g%' => self::VALUE_CODE_GENRE,
		                      '%a%' => self::VALUE_CODE_APPELLATION,
		                      '%m%' => self::VALUE_CODE_MENTION,
		                      '%l%' => self::VALUE_CODE_LIEU,
		                      '%co%' => self::VALUE_CODE_COULEUR,
		                      '%ce%' => self::VALUE_CODE_CEPAGE);

		$code = $format;

		foreach($format_index as $key => $item) {
		  if (isset($codes[$item])) {
		    $code = trim(str_replace($key, $codes[$item], $code));
		  } else {
		    $code = trim(str_replace($key, "", $code));
		  }
		}

		return $code;
  	}

  	public function formatProduits($produits, $format = "%g% %a% %l% %co% %ce% (%code%)") {
  		$produits_format = array();
  		foreach($produits as $produit) {
  			$produits_format[$produit->key[self::KEY_HASH]] = $this->formatProduit($produit, $format);
        }
        ksort($produits_format);

        return $produits_format;
  	}

  	protected function formatProduit($produit, $format = "%g% %a% %l% %co% %ce%") {
  		
        return $this->formatLibelles($produit->value->libelles, $format).' ('.$produit->key[self::KEY_CODE].')';
  	}
	
}  