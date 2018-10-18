<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VracImportCsvEdi
 *
 */
class VracImportCsvEdi extends VracCsvEdi {

    protected $configuration = null;
    protected $mouvements = array();
    protected $csvDoc = null;

    public function __construct($file, Vrac $vrac = null) 
    {
        $this->configuration = ConfigurationClient::getCurrent();
        if(is_null($this->csvDoc)) {
            $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromVrac($file, $vrac);
        }
        parent::__construct($file, $vrac);
    }

    public function getCsvDoc() 
    {
        return $this->csvDoc;
    }

    public function getDocRows() 
    {
        return $this->getCsv($this->csvDoc->getFileContent());
    }
    
    public function checkCSV() {
        $this->csvDoc->clearErreurs();
        $this->checkCSVIntegrity();
        
        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_ERREUR);
            $this->csvDoc->save();
            return;
        }
        
        $this->csvDoc->setStatut(self::STATUT_VALIDE);
        $this->csvDoc->save();
    }
    
    public function importCsv()
    {
        $numLigne = 0;
    	foreach ($this->getDocRows() as $csvRow) {
            if (preg_match('/^#/', $csvRow[self::CSV_VISA])) {
                continue;
            }
            $numLigne++;
            if ($this->vrac->isNew()) {
            	$this->importVrac($numLigne, $csvRow);
            } else {
            	$this->updateVrac($numLigne, $csvRow);
            }
    	}
    	if ($this->csvDoc->hasErreurs()) {
    		$this->csvDoc->setStatut(self::STATUT_ERREUR);
    		$this->csvDoc->save();
    		return;
    	}
    }
    
    private function updateVrac($numLigne, $datas)
    {
    	$this->vrac->volume_propose = ($datas[self::CSV_CAVE_VOLINITIAL])? round($this->floatize($datas[self::CSV_CAVE_VOLINITIAL]), 2) : 0;
    	$this->vrac->volume_enleve = ($datas[self::CSV_CAVE_VOLRETIRE])? round($this->floatize($datas[self::CSV_CAVE_VOLRETIRE]), 2) : 0;
    }
    
    private function importVrac($numLigne, $datas)
  	{
  		$identifiant = trim($datas[self::CSV_IDENTIFIANTVENDEUR]);
  		$ea = trim($datas[self::CSV_ACCISESVENDEUR]);
  		$siretCvi = null;
  		if (preg_match('/([a-zA-Z0-9\ \-\_]*)\(([a-zA-Z0-9\ \-\_]*)\)/', $identifiant, $result)) {
  			$identifiant = trim($result[1]);
  			$siretCvi = trim($result[2]);
  		}
  		$result = $this->vrac->setImportableVendeur($identifiant, $ea, $siretCvi);
  		if (!$result) {
  			$this->csvDoc->addErreur($this->etablissementNotFoundError($numLigne, $datas));
  			return;
  		}
		$libelle = $this->getKey($datas[self::CSV_CAVE_PRODUIT]);
		$configurationProduit = $this->configuration->identifyProduct($this->getHashProduit($datas), $libelle);
    	if (!$configurationProduit) {
    		$this->csvDoc->addErreur($this->productNotFoundError($numLigne, $datas));
    		return;
  		}
  		
  		$this->vrac->produit = $configurationProduit->getHash();
  		$this->vrac->setDetailProduit($configurationProduit);
  		$this->vrac->produit_libelle = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles());
  		
		$this->vrac->acheteur->nom = $datas[self::CSV_RSACHETEUR];
		$this->vrac->acheteur->raison_sociale = $datas[self::CSV_RSACHETEUR];
  		$this->vrac->numero_contrat = $datas[self::CSV_VISA];
  		$this->vrac->millesime = $datas[self::CSV_CAVE_MILLESIME];
  		$this->vrac->volume_propose = ($datas[self::CSV_CAVE_VOLINITIAL])? round($this->floatize($datas[self::CSV_CAVE_VOLINITIAL]), 2) : 0;
  		$this->vrac->volume_enleve = ($datas[self::CSV_CAVE_VOLRETIRE])? round($this->floatize($datas[self::CSV_CAVE_VOLRETIRE]), 2) : 0;
    }

    private function checkCSVIntegrity() {
        $ligne_num = 0;
        foreach ($this->getDocRows() as $csvRow) {
            if (preg_match('/^#/', $csvRow[self::CSV_VISA])) {
                continue;
            }
            $ligne_num++;
            if ($csvRow[self::CSV_ACCISESVENDEUR] && !preg_match('/^FR[a-zA-Z0-9]{11}$/', $csvRow[self::CSV_ACCISESVENDEUR])) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
            }
            if ($csvRow[self::CSV_CAVE_MILLESIME] && !preg_match('/^[0-9]{4}$/', $csvRow[self::CSV_CAVE_MILLESIME])) {
                $this->csvDoc->addErreur($this->createWrongFormatMillesimeError($ligne_num, $csvRow));
            }
        }
    }
    
    private function etablissementNotFoundError($num_ligne, $csvRow) {
    	return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_IDENTIFIANTVENDEUR]), "Impossible d'identifier l'établissement");
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_ACCISESVENDEUR]), "Format numéro d'accises non valide");
    }

    private function createWrongFormatMillesimeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_ACCISESVENDEUR]), "Format numéro d'accises non valide");
    }

    private function productNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_PRODUIT], "Le produit n'a pas été trouvé");
    }

    private function createError($num_ligne, $erreur_csv, $raison) {
        $error = new stdClass();
        $error->num_ligne = $num_ligne;
        $error->erreur_csv = $erreur_csv;
        $error->raison = $raison;
        return $error;
    }

    private function getHashProduit($datas)
    {
    	if (
    			!$this->getKey($datas[self::CSV_CAVE_CERTIFICATION]) &&
    			!$this->getKey($datas[self::CSV_CAVE_GENRE]) &&
    			!$this->getKey($datas[self::CSV_CAVE_APPELLATION]) &&
    			!$this->getKey($datas[self::CSV_CAVE_MENTION]) &&
    			!$this->getKey($datas[self::CSV_CAVE_LIEU]) &&
    			!$this->couleurKeyToCode($datas[self::CSV_CAVE_COULEUR], false) && 
    			!$this->getKey($datas[self::CSV_CAVE_CEPAGE])
    		) {
    		return null;
    	}
    	$libelle = trim($datas[self::CSV_CAVE_PRODUIT]);
    	$libelles = ($libelle)? explode(' ', $libelle) : array();
    	foreach ($libelles as $k => $libelle) {
    		$libelles[$k] = $this->getKey($libelle);
    	}
 		if (
 				$libelles && (
    			in_array($this->getKey($datas[self::CSV_CAVE_GENRE]), $libelles) ||
 				in_array($this->getKey($datas[self::CSV_CAVE_APPELLATION]), $libelles) ||
 				in_array($this->getKey($datas[self::CSV_CAVE_LIEU]), $libelles) ||
 				in_array($this->getKey($datas[self::CSV_CAVE_CEPAGE]), $libelles))
    		) {
    		return null;
    	}
    	$hash = 'declaration/certifications/'.$this->getKey($datas[self::CSV_CAVE_CERTIFICATION]).
    	'/genres/'.$this->getKey($datas[self::CSV_CAVE_GENRE], true).
    	'/appellations/'.$this->getKey($datas[self::CSV_CAVE_APPELLATION], true).
    	'/mentions/'.$this->getKey($datas[self::CSV_CAVE_MENTION], true).
    	'/lieux/'.$this->getKey($datas[self::CSV_CAVE_LIEU], true).
    	'/couleurs/'.strtolower($this->couleurKeyToCode($datas[self::CSV_CAVE_COULEUR])).
    	'/cepages/'.$this->getKey($datas[self::CSV_CAVE_CEPAGE], true);
    	return $hash;
    }
     
    private function getKey($key, $withDefault = false)
    {
    	if ($key == " " || !$key) {
    		$key = null;
    	}
    	if ($withDefault) {
    		return ($key)? $key : ConfigurationProduit::DEFAULT_KEY;
    	} else {
    		return $key;
    	}
    }
    
    private function couleurKeyToCode($key , $withDefault = true)
    {
    	$key = strtolower($key);
    	if (preg_match('/^ros.+$/', $key)) {
    		$key = 'rose';
    	}
    	if (!$withDefault && ($key == " " || !$key)) {
    		return null;
    	}
    	$correspondances = array(1 => "rouge",
    			2 => "rose",
    			3 => "blanc");
    	if (!in_array($key, array_keys($correspondances))) {
    		return $this->getKey($key, true);
    	}
    	return $correspondances[$key];
    }	


  	private function floatize($value)
  	{
  		if ($value === null) {
  			return null;
  		}
  		return floatval(str_replace(',', '.', $value));
  	}

}
