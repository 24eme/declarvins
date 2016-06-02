<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMImportCsvEdi
 *
 * @author mathurin
 */
class DRMImportCsvEdiNew extends DRMCsvEdi {

    protected $configuration = null;
    protected $mouvements = array();
    protected $csvDoc = null;

    public function __construct($file, DRM $drm = null) 
    {
        $this->configuration = ConfigurationClient::getCurrent();
        if(is_null($this->csvDoc)) {
            $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromDRM($file, $drm);
        }
        parent::__construct($file, $drm);
    }

    public function getCsvDoc() {
        return $this->csvDoc;
    }

    public function getDocRows() {
        return $this->getCsv($this->csvDoc->getFileContent());
    }

    /**
     * CHECK DU CSV
     */
    public function checkCSV() {
        $this->csvDoc->clearErreurs();
        $this->checkCSVIntegrity();
        
        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_ERREUR);
            $this->csvDoc->save();
            return;
        }
        

        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_WARNING);
            $this->csvDoc->save();
            return;
        }
        $this->csvDoc->setStatut(self::STATUT_VALIDE);
        $this->csvDoc->save();
    }

    /**
     * IMPORT DEPUIS LE CSV
     */
    public function importCSV1($withSave = true) {
        $this->importAnnexesFromCSV();

        $this->importMouvementsFromCSV();
        $this->importCrdsFromCSV();
        //$this->drm->teledeclare = true;
        $this->drm->etape = DRMClient::ETAPE_VALIDATION;
        $this->drm->type_creation = DRMClient::DRM_CREATION_EDI;
        $this->drm->buildFavoris();
        $this->drm->storeDeclarant();
        $this->drm->initSociete();
        $this->updateAndControlCoheranceStocks();

        if($withSave) {
            $this->drm->save();
        }
    }

    public function updateAndControlCoheranceStocks() {

        $this->drm->update();

        if ($this->csvDoc->hasErreurs()) {
            $this->csvDoc->setStatut(self::STATUT_WARNING);
            $this->csvDoc->save();
        }
    }
    
    public function importCsv()
    {
        $numLigne = 0;
    	foreach ($this->getDocRows() as $csvRow) {
            $numLigne++;
            if ($numLigne == 1 && KeyInflector::slugify($csvRow[self::CSV_TYPE]) == 'TYPE') {
                continue;
            }
    		switch($csvRow[self::CSV_TYPE]) {
    			case self::TYPE_CAVE:
    				$this->importCave($numLigne, $csvRow);
    				break;
    			case self::TYPE_CONTRAT:
    				$this->importRetiraison($numLigne, $csvRow);
    				break;
    			case self::TYPE_CRD:
    				$this->importCrd($numLigne, $csvRow);
    				break;
    			case self::TYPE_ANNEXE:
    				$this->importAnnexe($numLigne, $csvRow);
    				break;
    			default:
    				break;
    		}
    	}
    	if ($this->csvDoc->hasErreurs()) {
    		$this->csvDoc->setStatut(self::STATUT_ERREUR);
    		$this->csvDoc->save();
    		return;
    	}
    }
    
    private function importCave($numLigne, $datas)
  	{
		$hash = $this->getHashProduit($datas);
    	if (!$this->configuration->getConfigurationProduit($hash)) {
    		$this->csvDoc->addErreur($this->productNotFoundError($numLigne, $datas));
    		return;
  		}
  		$produit = $this->drm->addProduit($hash, array());
  		
  		$categorieMvt = $datas[self::CSV_CAVE_CATEGORIE_MOUVEMENT];
  		$typeMvt = $datas[self::CSV_CAVE_TYPE_MOUVEMENT];
  		
  		if ($this->mouvements) {
	  		if ($categorieMvt && !array_key_exists($categorieMvt, $this->mouvements)) {
	  			$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
	  		if (!array_key_exists($typeMvt, $this->mouvements[$categorieMvt])) {
	  			$this->csvDoc->addErreur($this->typeMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
  		} else {
  			if ($categorieMvt && !$produit->exist($categorieMvt)) {
	  			$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
  			if ($categorieMvt && !$produit->get($categorieMvt)->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		} elseif(!$produit->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
  		}
  		
  		$mvt = ($categorieMvt)? $produit->getOrAdd($categorieMvt) : $produit;
  		$mvt->add($typeMvt, round($this->floatize($datas[self::CSV_CAVE_VOLUME]), 2));
    	echo $hash." ADDED\n";
    }
    
    private function importRetiraison($numLigne, $datas)
  	{
  		echo 'retiraison'."\n";
    }
    
    private function importCrd($numLigne, $datas)
  	{
  		echo 'crd'."\n";
    }
    
    private function importAnnexe($numLigne, $datas)
  	{
    	echo 'annexe'."\n";
    }
    
    /*
     * OK
     */

    private function checkCSVIntegrity() {
        $ligne_num = 1;
        $periodes = array();
        $accises = array();
        foreach ($this->getDocRows() as $csvRow) {
            if ($ligne_num == 1 && KeyInflector::slugify($csvRow[self::CSV_TYPE]) == 'TYPE') {
                $ligne_num++;
                continue;
            }
            if (!in_array($csvRow[self::CSV_TYPE], self::$permitted_types)) {
                $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^[0-9]{4}-[0-9]{2}$/', $csvRow[self::CSV_PERIODE])) {
                $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
            } else {
            	$periodes[$csvRow[self::CSV_PERIODE]] = 1;
            }
            if (!preg_match('/^FR0[0-9]{10}$/', $csvRow[self::CSV_NUMACCISE])) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
            } else {
            	$accises[$csvRow[self::CSV_NUMACCISE]] = 1;
            }
            $ligne_num++;
        }
        if (count($periodes) > 1) {
        	$this->csvDoc->addErreur($this->createMultiPeriodeError());
        }
        if (count($accises) > 1) {
        	$this->csvDoc->addErreur($this->createMultiAcciseError());
        }
    }

    /**
     * Functions de création d'erreurs
     */
    private function createMultiPeriodeError() {
        return $this->createError(0, 'DRM', "Import limité à une seule période");
    }
    private function createMultiAcciseError() {
        return $this->createError(0, 'DRM', "Import limité à un seul numéro d'EA");
    }
    
    private function createWrongFormatTypeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_TYPE]), "Choix possible type : " . implode(', ', self::$permitted_types));
    }

    private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_PERIODE]), "Format période : AAAA-MM");
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]), "Format numéro d'accise : FR0XXXXXXXXXX");
    }

    private function productNotFoundError($num_ligne, $csvRow) {
        $libellesArray = $this->buildLibellesArrayWithRow($csvRow);
        return $this->createError($num_ligne, implode(' ', $libellesArray), "Le produit n'a pas été trouvé");
    }

    private function categorieMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT], "Le catégorie de mouvement n'a pas été trouvé");
    }

    private function typeMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT], "Le type de mouvement n'a pas été trouvé");
    }

    private function exportPaysNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_EXPORTPAYS], "Le pays d'export n'a pas été trouvé");
    }

    private function contratIDEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "L'id du contrat ne peut pas être vide");
    }

    private function contratIDNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Le contrat n'a pas été trouvé");
    }

    private function observationsEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, "Observations", "Les observations sont vides.");
    }

    private function sucreWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La quantité de sucre est nulle ou possède un mauvais format.");
    }

    private function typeDocumentWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le type de document d'annexe n'est pas connu.");
    }

    private function annexesTypeMvtWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'enregistrement des " . $csvRow[self::CSV_ANNEXE_TYPEANNEXE] . " doit être 'début' ou 'fin' .");
    }

    private function annexesNumeroDocumentError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le numéro de document ne peut pas être vide.");
    }

    private function annexesNonApurementWrongDateError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION], "La date est vide ou mal formattée.");
    }

    private function annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST], "La numéro d'accise du destinataire est vide ou mal formatté.");
    }

    private function createError($num_ligne, $erreur_csv, $raison) {
        $error = new stdClass();
        $error->num_ligne = $num_ligne;
        $error->erreur_csv = $erreur_csv;
        $error->raison = $raison;
        return $error;
    }
    
    private function buildLibellesArrayWithRow($csvRow, $with_slugify = false) {
        $certification = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_CERTIFICATION]) : $csvRow[self::CSV_CAVE_CERTIFICATION];
        $genre = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_GENRE]) : $csvRow[self::CSV_CAVE_GENRE];
        $appellation = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_APPELLATION]) : $csvRow[self::CSV_CAVE_APPELLATION];
        $mention = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_APPELLATION]) : $csvRow[self::CSV_CAVE_APPELLATION];
        $lieu = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_LIEU]) : $csvRow[self::CSV_CAVE_LIEU];
        $couleur = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_COULEUR]) : $csvRow[self::CSV_CAVE_COULEUR];
        $cepage = ($with_slugify) ? KeyInflector::slugify($csvRow[self::CSV_CAVE_CEPAGE]) : $csvRow[self::CSV_CAVE_CEPAGE];
        $libelles = array($certification,
            $genre,
            $appellation,
            $mention,
            $lieu,
            $couleur,
            $cepage);
        foreach ($libelles as $key => $libelle) {
            if (!$libelle) {
                $libelles[$key] = null;
            }
        }
        return $libelles;
    }
    
    /**
     * NEW
     */
    


    private function getHashProduit($datas)
    {
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
    
    private function couleurKeyToCode($key)
    {
    	$correspondances = array(1 => "rouge",
    			2 => "rose",
    			3 => "blanc");
    	if (!in_array($key, array_keys($correspondances))) {
    		return $key;
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
