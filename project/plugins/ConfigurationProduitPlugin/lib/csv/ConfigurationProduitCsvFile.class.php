<?php

class ConfigurationProduitCsvFile extends CsvFile 
{
	const CSV_PRODUIT_CATEGORIE_LIBELLE = 0;    //CATEGORIE == CERTIFICATION
  	const CSV_PRODUIT_CATEGORIE_CODE = 1;       //CATEGORIE == CERTIFICATION
  	const CSV_PRODUIT_GENRE_LIBELLE = 2;
  	const CSV_PRODUIT_GENRE_CODE = 3;
  	const CSV_PRODUIT_DENOMINATION_LIBELLE = 4; //DENOMINATION == APPELLATION
  	const CSV_PRODUIT_DENOMINATION_CODE = 5;    //DENOMINATION == APPELLATION
  	const CSV_PRODUIT_LIEU_LIBELLE = 6;
  	const CSV_PRODUIT_LIEU_CODE = 7;
  	const CSV_PRODUIT_COULEUR_LIBELLE = 8;
  	const CSV_PRODUIT_COULEUR_CODE = 9;
  	const CSV_PRODUIT_CEPAGE_LIBELLE = 10;
  	const CSV_PRODUIT_CEPAGE_CODE = 11;
  	const CSV_PRODUIT_LABELS = 12;
  	const CSV_PRODUIT_DEPARTEMENTS = 13;
  	const CSV_PRODUIT_DROITS_DOUANE = 14;
  	const CSV_PRODUIT_DROITS_CVO = 15;
  	const CSV_PRODUIT_DRM_VRAC = 16;
  	const CSV_PRODUIT_OIOC = 17;
  	const CSV_PRODUIT_DRM_CONFIG_ENTREE_REPLI = 18;
  	const CSV_PRODUIT_DRM_CONFIG_SORTIE_REPLI = 19;
  	const CSV_PRODUIT_DRM_CONFIG_ENTREE_DECLASSEMENT = 20;
  	const CSV_PRODUIT_DRM_CONFIG_SORTIE_DECLASSEMENT = 21;
  	
  	const CSV_PRODUIT_DROITS_CODE = 0;
  	const CSV_PRODUIT_DROITS_LIBELLE = 1;
  	const CSV_PRODUIT_DROITS_TAUX = 2;
  	const CSV_PRODUIT_DROITS_DATE = 3;
  	const CSV_PRODUIT_DROITS_NOEUD = 4;
  	
  	const CSV_PRODUIT_OIOC_OIOC = 0;
  	const CSV_PRODUIT_OIOC_DATE = 1;
  	
  	
  	const CSV_DELIMITER_DEPARTEMENTS = ',';
  	const CSV_DELIMITER_LABELS = '|';
  	const CSV_DELIMITER_LABELS_INTER = ':';
  	const CSV_DELIMITER_DROITS = '|';
  	const CSV_DELIMITER_DROITS_INTER = '/';
  	const CSV_DELIMITER_OIOC = '|';
  	const CSV_DELIMITER_OIOC_INTER = '/';
  	
  	protected static $csv_produits_entetes = array (
  		'#CATEGORIE_LIBELLE',
  		'CATEGORIE_CODE',
  		'GENRE_LIBELLE',
  		'GENRE_CODE',
  		'DENOMINATION_LIBELLE',
  		'DENOMINATION_CODE',
  		'LIEU_LIBELLE',
  		'LIEU_CODE',
  		'COULEUR_LIBELLE',
  		'COULEUR_CODE',
  		'CEPAGE_LIBELLE',
  		'CEPAGE_CODE',
  		'LABELS',
  		'DEPARTEMENTS',
  		'DROITS_DOUANE',
  		'DROITS_CVO',
  		'DRM_VRAC',
  		'OIOC',
  		'REPLI_ENTREE',
  		'REPLI_SORTIE',
  		'DECLASSEMENT_ENTREE',
  		'DECLASSEMENT_SORTIE'
	);

    public static function getCsvProduitsEntetes() 
    {
		return self::$csv_produits_entetes;
    }
  
	protected $config;
  	protected $errors;
  
  	public function __construct($config, $file = null) 
  	{
    	parent::__construct($file);
    	$this->config = $config;
    	$this->errors = array();
  	}
  	
  	public function exportProduits()
  	{
  		$produits = $this->config->declaration->getProduits();
  		$result = array();
  		$i = 0;
  		$result[$i] = self::getCsvProduitsEntetes();
  		foreach ($produits as $produit) {
  			$i++;
  			$result[$i][self::CSV_PRODUIT_CATEGORIE_LIBELLE] = $produit->getCertification()->libelle;
  			$result[$i][self::CSV_PRODUIT_CATEGORIE_CODE] = $this->getExportKey($produit->getCertification()->code);
  			$result[$i][self::CSV_PRODUIT_GENRE_LIBELLE] = $produit->getGenre()->libelle;
  			$result[$i][self::CSV_PRODUIT_GENRE_CODE] = $this->getExportKey($produit->getGenre()->code);
  			$result[$i][self::CSV_PRODUIT_DENOMINATION_LIBELLE] = $produit->getAppellation()->libelle;
  			$result[$i][self::CSV_PRODUIT_DENOMINATION_CODE] = $this->getExportKey($produit->getAppellation()->code);
  			$result[$i][self::CSV_PRODUIT_LIEU_LIBELLE] = $produit->getLieu()->libelle;
  			$result[$i][self::CSV_PRODUIT_LIEU_CODE] = $this->getExportKey($produit->getLieu()->code);
  			$result[$i][self::CSV_PRODUIT_COULEUR_LIBELLE] = $produit->getCouleur()->libelle;
  			$result[$i][self::CSV_PRODUIT_COULEUR_CODE] = $this->getExportKey($produit->getCouleur()->code);
  			$result[$i][self::CSV_PRODUIT_CEPAGE_LIBELLE] = $produit->getCepage()->libelle;
  			$result[$i][self::CSV_PRODUIT_CEPAGE_CODE] = $this->getExportKey($produit->getCepage()->code);
		  	$result[$i][self::CSV_PRODUIT_LABELS] = $this->renderCsvLabels($produit->getCurrentLabels());
		  	$result[$i][self::CSV_PRODUIT_DEPARTEMENTS] = $this->renderCsvDepartements($produit->getCurrentDepartements());
		  	$result[$i][self::CSV_PRODUIT_DROITS_DOUANE] = $this->renderCsvDroits($produit->getHistoryDroit(ConfigurationProduit::NOEUD_DROIT_DOUANE));
		  	$result[$i][self::CSV_PRODUIT_DROITS_CVO] = $this->renderCsvDroits($produit->getHistoryDroit(ConfigurationProduit::NOEUD_DROIT_CVO));
		  	$result[$i][self::CSV_PRODUIT_DRM_VRAC] = $this->renderCsvDrmVrac($produit->getCurrentDrmVrac());
		  	$result[$i][self::CSV_PRODUIT_OIOC] = $this->renderCsvOrganismes($produit->getHistoryOrganisme());
		  	$drmConf = $produit->getCurrentDefinitionDrm();
		  	$er = null;
		  	$sr = null;
		  	$ed = null;
		  	$sd = null;
		  	if ($drmConf) {
		    	$drmConf = current($drmConf);
		    	$er = ($drmConf->entree->repli)? 1 : 0;
		    	$sr = ($drmConf->sortie->repli)? 1 : 0;
		    	$ed = ($drmConf->entree->declassement)? 1 : 0;
		    	$sd = ($drmConf->sortie->declassement)? 1 : 0;
		  	}
		  	$result[$i][self::CSV_PRODUIT_DRM_CONFIG_ENTREE_REPLI] = $er;
		  	$result[$i][self::CSV_PRODUIT_DRM_CONFIG_SORTIE_REPLI] = $sr;
		  	$result[$i][self::CSV_PRODUIT_DRM_CONFIG_ENTREE_DECLASSEMENT] = $ed;
		  	$result[$i][self::CSV_PRODUIT_DRM_CONFIG_SORTIE_DECLASSEMENT] = $sd;
  		}
  		return $result;
  	}
  	
  	protected function renderCsvLabels($labels)
  	{
  		if (!$labels) {
  			return null;
  		}
  		$labels = current($labels);
  		$nbLabels = count($labels);
  		$result = '';
  		$counter = 0;
  		foreach ($labels as $code => $label) {
  			$counter++;
  			$result .= $code.self::CSV_DELIMITER_LABELS_INTER.$label;
  			if ($counter < $nbLabels) {
  				$result .= self::CSV_DELIMITER_LABELS;
  			}
  		}
  		return $result;
  	}
  	
  	protected function renderCsvDepartements($departements)
  	{
  		if (!$departements) {
  			return null;
  		}
  		$departements = current($departements);
  		$nbDepartements = count($departements);
  		$result = '';
  		$counter = 0;
  		foreach ($departements as $departement) {
  			$counter++;
  			$result .= $departement;
  			if ($counter < $nbDepartements) {
  				$result .= self::CSV_DELIMITER_DEPARTEMENTS;
  			}
  		}
  		return $result;
  	}
  	
  	protected function renderCsvDrmVrac($drmVrac)
  	{
  		if (!$drmVrac) {
  			return null;
  		}
  		$drmVrac = current($drmVrac);
  		return ($drmVrac)? 1 : 0;
  	}
  	
  	protected function renderCsvDroits($items)
  	{
  		if (!$items) {
  			return null;
  		}
  		$result = '';
  		$nbNoeuds = count($items);
  		$i = 0;
  		foreach ($items as $code => $droits) {
  			$i++;
  			if (!$droits) {
  				continue;
  			}
  			$nbDroits = count($droits);
  			$counter = 0;
  			foreach ($droits as $droit) {
  				$counter++;
  				$result .= $droit->code.self::CSV_DELIMITER_DROITS_INTER.$droit->libelle.self::CSV_DELIMITER_DROITS_INTER.$droit->taux.self::CSV_DELIMITER_DROITS_INTER.$droit->date.self::CSV_DELIMITER_DROITS_INTER.$code;
	  			if ($counter < $nbDroits) {
	  				$result .= self::CSV_DELIMITER_DROITS;
	  			}
  			}
  			if ($i < $nbNoeuds) {
	  			$result .= self::CSV_DELIMITER_DROITS;
	  		}
  		}
  		return $result;
  	}
  	
  	protected function renderCsvOrganismes($items)
  	{
  		if (!$items) {
  			return null;
  		}
  		$result = '';
  		$nbNoeuds = count($items);
  		$i = 0;
  		foreach ($items as $code => $organismes) {
  			$i++;
  			if (!$organismes) {
  				continue;
  			}
  			$nbOrganismes = count($organismes);
  			$counter = 0;
  			foreach ($organismes as $organisme) {
  				$counter++;
  				$result .= $organisme->oioc.self::CSV_DELIMITER_OIOC_INTER.$organisme->date;
	  			if ($counter < $nbOrganismes) {
	  				$result .= self::CSV_DELIMITER_OIOC;
	  			}
  			}
  			if ($i < $nbNoeuds) {
	  			$result .= self::CSV_DELIMITER_OIOC;
	  		}
  		}
  		return $result;
  	}

	public function importProduits() 
  	{	    
    	$csv = $this->getCsv();
    	$ligne = 0;
		foreach ($csv as $line) {
			$ligne++;
			try {
				$produit = $this->getProduit($line);
				$produit->setDonneesCsv($line);
			} catch(Execption $e) {
    			$this->errors[$ligne] = $e->getMessage();
    		}
      	}
    	return $this->config;
  	}

  	private function getProduit($line) 
  	{
  		$hash = 'certifications/'.$this->getKey($line[self::CSV_PRODUIT_CATEGORIE_CODE]).
                '/genres/'.$this->getKey($line[self::CSV_PRODUIT_GENRE_CODE], true).
                '/appellations/'.$this->getKey($line[self::CSV_PRODUIT_DENOMINATION_CODE], true).
                '/mentions/'.ConfigurationProduit::DEFAULT_KEY.
                '/lieux/'.$this->getKey($line[self::CSV_PRODUIT_LIEU_CODE], true).
                '/couleurs/'.strtolower($this->couleurKeyToCode($line[self::CSV_PRODUIT_COULEUR_CODE])).
                '/cepages/'.$this->getKey($line[self::CSV_PRODUIT_CEPAGE_CODE], true);
    	return $this->config->declaration->getOrAdd($hash);
  	}
  
  	private function couleurKeyToCode($key) 
  	{
    	$correspondances = ConfigurationProduit::getCorrespondanceCouleurs();
    	return $correspondances[$key];
  	}
  
	private function getKey($key, $withDefault = false) 
	{
		if ($withDefault) {
			return ($key)? $key : ConfigurationProduit::DEFAULT_KEY;
  		} else {
  			return $key;
  		}
  	}
  
	private function getExportKey($key) 
	{
		return ($key == ConfigurationProduit::DEFAULT_KEY)? null : $key;
  	}

	public function getErrors() 
  	{
    	return $this->errors;
  	}
  	
  	public function hasErrors()
  	{
  		return (count($this->errors) > 0)? true : false; 
  	}
}