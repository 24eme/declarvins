<?php

class ProduitCsvFile extends CsvFile 
{
  const CSV_PRODUIT_INTERPRO = 0;
  const CSV_PRODUIT_CATEGORIE_LIBELLE = 1;    //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE = 2;       //CATEGORIE == CERTIFICATION
  const CSV_PRODUIT_CATEGORIE_CODE_APPLICATIF_DROIT = 'C';
  const CSV_PRODUIT_GENRE_LIBELLE = 3;
  const CSV_PRODUIT_GENRE_CODE = 4;
  const CSV_PRODUIT_GENRE_CODE_APPLICATIF_DROIT = 'G';
  const CSV_PRODUIT_DENOMINATION_LIBELLE = 5; //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE = 6;    //DENOMINATION == APPELLATION
  const CSV_PRODUIT_DENOMINATION_CODE_APPLICATIF_DROIT = 'A';
  /*const CSV_PRODUIT_MENTION_LIBELLE = 7;
  const CSV_PRODUIT_MENTION_CODE = 8;*/
  const CSV_PRODUIT_MENTION_CODE_APPLICATIF_DROIT = 'M';
  const CSV_PRODUIT_LIEU_LIBELLE = 7;
  const CSV_PRODUIT_LIEU_CODE = 8;
  const CSV_PRODUIT_LIEU_CODE_APPLICATIF_DROIT = 'L';
  const CSV_PRODUIT_COULEUR_LIBELLE = 9;
  const CSV_PRODUIT_COULEUR_CODE = 10;
  const CSV_PRODUIT_COULEUR_CODE_APPLICATIF_DROIT = 'CO';
  const CSV_PRODUIT_CEPAGE_LIBELLE = 11;
  const CSV_PRODUIT_CEPAGE_CODE = 12;
  const CSV_PRODUIT_DEPARTEMENTS = 13;
  const CSV_PRODUIT_DOUANE_CODE = 14;
  const CSV_PRODUIT_DOUANE_LIBELLE = 15;
  const CSV_PRODUIT_DOUANE_TAXE = 16;
  const CSV_PRODUIT_DOUANE_DATE = 17;
  const CSV_PRODUIT_DOUANE_NOEUD = 18;
  const CSV_PRODUIT_CVO_CODE = 19;
  const CSV_PRODUIT_CVO_LIBELLE = 20;
  const CSV_PRODUIT_CVO_TAXE = 21;
  const CSV_PRODUIT_CVO_DATE = 22;
  const CSV_PRODUIT_CVO_NOEUD = 23;
  const CSV_PRODUIT_REPLI_ENTREE = 24;
  const CSV_PRODUIT_REPLI_SORTI = 25;
  const CSV_PRODUIT_DECLASSEMENT_ENTREE = 26;
  const CSV_PRODUIT_DECLASSEMENT_SORTI = 27;
  const CSV_PRODUIT_OIOC = 28;
  const CSV_HAS_VRAC = 29;
  
  protected $config;
  protected $errors;
  protected $interpros;
  
  public function __construct($config, $file) {
    parent::__construct($file);
    $this->config = $config;
    $this->interpros = array();
  }

  private function getProduit($line) 
  {
  	try {
  	$hash = 'certifications/'.$this->getKey($line[self::CSV_PRODUIT_CATEGORIE_CODE]).
                '/genres/'.$this->getKey($line[self::CSV_PRODUIT_GENRE_CODE], true).
                '/appellations/'.$this->getKey($line[self::CSV_PRODUIT_DENOMINATION_CODE], true).
                '/mentions/'.ConfigurationProduit::DEFAULT_KEY.
                '/lieux/'.$this->getKey($line[self::CSV_PRODUIT_LIEU_CODE], true).
                '/couleurs/'.strtolower($this->couleurKeyToCode($line[self::CSV_PRODUIT_COULEUR_CODE])).
                '/cepages/'.$this->getKey($line[self::CSV_PRODUIT_CEPAGE_CODE], true);
  	
    return $this->config->declaration->getOrAdd($hash);
  	} catch (Exception $e) {
  		print_r($line);
  		echo "\n";
  	}
  }
  
  private function couleurKeyToCode($key) {
    $correspondances = array(1 => "rouge",
                             2 => "rose",
                             3 => "blanc");

    return $correspondances[$key];
  }
  
  private function getKey($key, $withDefault = false) 
  {
	if ($withDefault) {
  		return ($key)? $key : ConfigurationProduit::DEFAULT_KEY;
  	} elseif (!$key) {
  		throw new Exception('La clé "'.$key.'" n\'est pas valide');
  	} else {
  		return $key;
  	}
  }

  public function importProduits() 
  {    
    $this->errors = array();
    $csv = $this->getCsv();
    try {
		foreach ($csv as $line) {
			$produit = $this->getProduit($line);
			$produit->setDonneesCsv($line);
			$this->setDepartementsInterpros($line);
      	}
    } catch(Execption $e) {
    	$this->errors[] = $e->getMessage();
    }
    return $this->config;
  }

  public function getErrors() 
  {
    return $this->errors;
  }

  public function getInterpros() 
  {
    return $this->interpros;
  }

  public function getInterprosObject() 
  {
  	$interpros = InterproClient::getInstance()->getInterprosInitialConfiguration();
  	$inters = array();
  	foreach ($this->interpros as $interpro => $departement) {
  		if (isset($interpros[$interpro])) {
  			$i = $interpros[$interpro];
	    	$i->departements = $departement;
	    	$inters[] = $i;
  		}
  	}
    return $inters;
  }
  
  private function setDepartementsInterpros($datas)
  {
  	$interpros = $this->interpros;
  	$interpro = strtoupper($datas[self::CSV_PRODUIT_INTERPRO]);
  	$departements = $datas[self::CSV_PRODUIT_DEPARTEMENTS];
  	if (!$departements) {
  		$departements = array();
  	} else {
  		$departements = str_replace(' ', '', $departements);
  		$departements = explode(',', $departements);
  	}
  	if (!isset($interpros[$interpro])) {
  		$interpros[$interpro] = array();
  	}
  	$newTab = array_merge($interpros[$interpro], $departements);
  	$interpros[$interpro] = array_unique($newTab);
  	$this->interpros = $interpros;
  }
}