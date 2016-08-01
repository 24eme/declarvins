<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DRMImportCsvEdi
 *
 */
class DRMImportCsvEdi extends DRMCsvEdi {

    protected $configuration = null;
    protected $mouvements = array();
    protected $csvDoc = null;
    protected $permettedValues = null;

    public function __construct($file, DRM $drm = null, $permettedValues = array()) 
    {
        $this->configuration = ConfigurationClient::getCurrent();
        $this->permettedValues = $permettedValues;
        if(is_null($this->csvDoc)) {
            $this->csvDoc = CSVClient::getInstance()->createOrFindDocFromDRM($file, $drm);
        }
        parent::__construct($file, $drm);
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
    		if ($numLigne == 0) {
    			$this->importDRM($csvRow);
    		}
            $numLigne++;
            if ($numLigne == 1 && KeyInflector::slugify($csvRow[self::CSV_TYPE]) == 'TYPE') {
                continue;
            }
    		switch($csvRow[self::CSV_TYPE]) {
    			case self::TYPE_CAVE:
    				$this->importCave($numLigne, $csvRow);
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
    
    private function importDRM($datas)
  	{
  		$this->drm->setImportablePeriode($datas[self::CSV_PERIODE]);
  		$this->drm->setImportableIdentifiant($datas[self::CSV_IDENTIFIANT]);
    }
    
    private function importCave($numLigne, $datas)
  	{
		$hash = $this->getHashProduit($datas);
    	if (!$this->configuration->getConfigurationProduit($hash)) {
    		$this->csvDoc->addErreur($this->productNotFoundError($numLigne, $datas));
    		return;
  		}
  		$droits = $datas[self::CSV_CAVE_TYPE_DROITS];
  		if (!in_array($datas[self::CSV_CAVE_TYPE_DROITS], array(self::TYPE_DROITS_SUSPENDUS, self::TYPE_DROITS_ACQUITTES))) {
    		$this->csvDoc->addErreur($this->droitsNotFoundError($numLigne, $datas));
    		return;
  		}
  		
  		if ($complement = $datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]) {
  			if (isset($this->permettedValues[self::TYPE_CAVE]) && isset($this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT])) {
  				if (is_array($this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT]) && !in_array($complement, $this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT])) {
  					$this->csvDoc->addErreur($this->complementProductWrongFormatError($numLigne, $datas));
  					return;
  				}
  				if (!is_array($this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT]) && !preg_match($this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT], $complement)) {
  					$this->csvDoc->addErreur($this->complementProductWrongFormatError($numLigne, $datas));
  					return;
  				}
  			}
  		}
  		
  		$produit = $this->drm->addProduit($hash, $complement);
  		
  		$categorieMvt = $datas[self::CSV_CAVE_CATEGORIE_MOUVEMENT];
  		$typeMvt = $this->drm->getImportableLibelleMvt($datas[self::CSV_CAVE_TYPE_DROITS], $datas[self::CSV_CAVE_TYPE_MOUVEMENT]);
  		$valeur = $datas[self::CSV_CAVE_VOLUME];
  		
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
  				if (!$produit->exist($typeMvt)) {
		  			$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
		  			return;
  				} else {
  					$categorieMvt = null;
  				}
	  		}
  			if ($categorieMvt && !$produit->get($categorieMvt)->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->typeMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		} elseif(!$categorieMvt && !$produit->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->typeMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
  		}
  		
  		if (!$categorieMvt && $typeMvt == 'vrac') {
  			
  			$numContrat = $datas[self::CSV_CAVE_CONTRATID];
  			if (!$produit->hasSortieVrac()) {
  				$this->csvDoc->addErreur($this->retiraisonNotAllowedError($numLigne, $datas));
  				return;
  			}
  			$contrats = $produit->getContratsVrac();
  			$exist = false;
  			foreach ($contrats as $contrat) {
  				if ($numContrat == $contrat->getNumeroContrat()) {
  					$exist = true;
  					break;
  				}
  			}
  			if (!$exist) {
  				$this->csvDoc->addErreur($this->contratNotFoundError($numLigne, $datas));
  				return;
  			}
  			if (!is_numeric($valeur) || $valeur < 0) {
  				$this->csvDoc->addErreur($this->valeurMouvementNotValidError($numLigne, $datas));
  				return;
  			}
  			$produit->addVrac($numContrat, round($this->floatize($valeur), 2));
  			
  		} elseif (!$categorieMvt && $typeMvt == 'observations') {
  			if (!$valeur) {
  				$this->csvDoc->addErreur($this->observationsEmptyError($numLigne, $datas));
  				return;
  			}
  			$produit->setImportableObservations($valeur);
  		} elseif (!$categorieMvt && $typeMvt == 'premix') {
  			if (!is_numeric($valeur) || $valeur < 0) {
  				$this->csvDoc->addErreur($this->valeurMouvementNotValidError($numLigne, $datas));
  				return;
  			}
  			 
  			$mvt = ($categorieMvt)? $produit->getOrAdd($categorieMvt) : $produit;
  			$mvt->add($typeMvt, intval($valeur));
  		} else {
	  		if (!is_numeric($valeur) || $valeur < 0) {
		  		$this->csvDoc->addErreur($this->valeurMouvementNotValidError($numLigne, $datas));
		  		return;  			
	  		}
	  		
	  		$mvt = ($categorieMvt)? $produit->getOrAdd($categorieMvt) : $produit;
	  		$mvt->add($typeMvt, round($this->floatize($valeur), 2));
	  		$result = $this->drm->setImportableMvtDetails($typeMvt, $mvt, $datas);
	  		if (!$result) {
	  			$this->csvDoc->addErreur($this->mvtDetailsNotValidError($numLigne, $datas));
	  			return;
	  		}
  		}
    }
    
    private function importCrd($numLigne, $datas)
  	{
  		$categorie = $datas[self::CSV_CRD_COULEUR];
  		$type = $datas[self::CSV_CRD_TYPE_DROITS];
  		$centilisation = $datas[self::CSV_CRD_CENTILITRAGE];
  		
  		$categorieCrd = $datas[self::CSV_CRD_CATEGORIE_KEY];
  		$typeCrd = $datas[self::CSV_CRD_TYPE_KEY];
  		$valeur = $datas[self::CSV_CRD_QUANTITE];
  		
  		if (!$this->configuration->isCentilisationCrdAccepted($centilisation)) {
  			$this->csvDoc->addErreur($this->centilisationCrdNotFoundError($numLigne, $datas));	
  			return;
  		}
  		if (!$this->configuration->isCategorieCrdAccepted($categorie)) {
  			$this->csvDoc->addErreur($this->categorieCrdNotFoundError($numLigne, $datas));  	
  			return;			
  		}
  		if (!$this->configuration->isTypeCrdAccepted($type)) {
  			$this->csvDoc->addErreur($this->typeCrdNotFoundError($numLigne, $datas));  	
  			return;
  		}
  		
  		$crd = $this->drm->addCrd($categorie, $type, $centilisation);
  		
  		if ($categorieCrd && !$crd->exist($categorieCrd)) {
  			if (!$crd->exist($typeCrd)) {
	  			$this->csvDoc->addErreur($this->categorieCrdMvtNotFoundError($numLigne, $datas));
	  			return;
  			} else {
  				$categorieCrd = null;
  			}
  		}
  		if ($categorieCrd && !$crd->get($categorieCrd)->exist($typeCrd)) {
  			$this->csvDoc->addErreur($this->typeCrdMvtNotFoundError($numLigne, $datas));
  			return;
  		} elseif(!$categorieCrd && !$crd->exist($typeCrd)) {
  			$this->csvDoc->addErreur($this->typeCrdMvtNotFoundError($numLigne, $datas));
  			return;
  		}
  		
  		if (!is_numeric($valeur) || $valeur < 0 || intval($valeur) != $valeur) {
	  		$this->csvDoc->addErreur($this->valeurCrdMouvementNotValidError($numLigne, $datas));
	  		return;  			
  		}
  		
  		$mvt = ($categorieCrd)? $crd->getOrAdd($categorieCrd) : $crd;
  		$mvt->add($typeCrd, intval($valeur));
  		
    }
    
    private function importAnnexe($numLigne, $datas)
  	{
    	switch ($datas[self::CSV_ANNEXE_CATMVT]) {
    		case 'rna':
    			$this->importNonApurement($numLigne, $datas);
    			break;
    		
    		case 'empreinte':
    		case 'daa':
    		case 'dsa':
    			$this->importDocument($numLigne, $datas);
    			break;
    			
    		case 'statistiques':
    			$this->importStatistiques($numLigne, $datas);
    			break;
    			
    		default:
    			break;
    	}
    }
    
    private function importNonApurement($numLigne, $datas)
    {
    	$numero = $datas[self::CSV_ANNEXE_NUMERODOCUMENT];
    	$accises = $datas[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST];
    	$date = $datas[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION];
    	
    	
    	if (!preg_match('/^[A-Za-z]{2}[0-9A-Za-z]{11}$/', $accises)) {
    		$this->csvDoc->addErreur($this->annexesNonApurementWrongNumAcciseError($numLigne, $datas));
    		return;
    	}
    	
    	if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
    		$this->csvDoc->addErreur($this->annexesNonApurementWrongDateError($numLigne, $datas));
			return;
    	}
    	
    	if (!is_numeric($numero) || $numero < 0 || intval($numero) != $numero) {
    		$this->csvDoc->addErreur($this->numeroRnaAnnexeNotValidError($numLigne, $datas));
    		return;
    	}
    	
    	$this->drm->setImportableRna(intval($numero), $accises, $date);
    }
    
    private function importDocument($numLigne, $datas)
    {
    	$declaratif = $this->drm->getImportableDeclaratif();

    	$categorie = $datas[self::CSV_ANNEXE_CATMVT];
    	$type = $datas[self::CSV_ANNEXE_TYPEMVT];
    	$valeur = $datas[self::CSV_ANNEXE_QUANTITE];
    	
    	if (!$categorie || !$declaratif->exist($categorie)) {
    		$this->csvDoc->addErreur($this->categorieAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}
    	if (!$declaratif->get($categorie)->exist($type)) {
    		$this->csvDoc->addErreur($this->typeAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}
    	
    	if (!is_numeric($valeur) || $valeur < 0 || intval($valeur) != $valeur) {
    		$this->csvDoc->addErreur($this->valeurAnnexeNotValidError($numLigne, $datas));
    		return;
    	}
  		
  		$mvt = $declaratif->getOrAdd($categorie);
  		$mvt->add($type, intval($valeur));
    }
    
    private function importStatistiques($numLigne, $datas)
    {

    	$declaratif = $this->drm->getImportableDeclaratif();
    	
    	$categorie = $datas[self::CSV_ANNEXE_CATMVT];
    	$type = $datas[self::CSV_ANNEXE_TYPEMVT];
    	$valeur = $datas[self::CSV_ANNEXE_QUANTITE];
    	 
    	if (!$categorie || !$declaratif->exist($categorie)) {
    		$this->csvDoc->addErreur($this->categorieAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}
    	if (!$declaratif->get($categorie)->exist($type)) {
    		$this->csvDoc->addErreur($this->typeAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}
    	 
    	if (!is_numeric($valeur) || $valeur < 0) {
    		$this->csvDoc->addErreur($this->valeurStatistiqueNotValidError($numLigne, $datas));
    		return;
    	}
    	
    	$mvt = $declaratif->getOrAdd($categorie);
    	$mvt->add($type, round($this->floatize($valeur), 2));
    }

    private function checkCSVIntegrity() {
        $ligne_num = 1;
        $periodes = array();
        $accises = array();
        $identifiants = array();
        foreach ($this->getDocRows() as $csvRow) {
            if ($ligne_num == 1 && KeyInflector::slugify($csvRow[self::CSV_TYPE]) == 'TYPE') {
                $ligne_num++;
                continue;
            }
            if (!in_array($csvRow[self::CSV_TYPE], self::$permitted_types)) {
                $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^[0-9]{6}$/', $csvRow[self::CSV_PERIODE])) {
                $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
            } else {
            	$periodes[$csvRow[self::CSV_PERIODE]] = 1;
            }
            if (!preg_match('/^FR0[0-9]{10}$/', $csvRow[self::CSV_NUMACCISE])) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
            } else {
            	$accises[$csvRow[self::CSV_NUMACCISE]] = 1;
            }
            $identifiants[$csvRow[self::CSV_IDENTIFIANT]] = 1;
            $ligne_num++;
        }
        if (count($periodes) > 1) {
        	$this->csvDoc->addErreur($this->createMultiPeriodeError());
        }
        if (count($accises) > 1) {
        	$this->csvDoc->addErreur($this->createMultiAcciseError());
        }
        if (count($identifiants) > 1) {
        	$this->csvDoc->addErreur($this->createMultiIdentifiantError());
        }
    }
    
    private function createMultiPeriodeError() {
        return $this->createError(0, 'DRM', "Import limité à une seule période");
    }
    private function createMultiAcciseError() {
        return $this->createError(0, 'DRM', "Import limité à un seul numéro d'EA");
    }
    private function createMultiIdentifiantError() {
        return $this->createError(0, 'DRM', "Import limité à un seul établissement");
    }
    
    private function createWrongFormatTypeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_TYPE]), "Choix possible type : " . implode(', ', self::$permitted_types));
    }

    private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_PERIODE]), "Format période : AAAAMM");
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]), "Format numéro d'accises non valide");
    }
    
  	private function centilisationCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CENTILITRAGE], "La centilisation CRD n'a pas été reconnue");
  	}
  	
  	private function categorieCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_TYPE_DROITS], "La catégorie fiscale CRD n'a pas été reconnue");
  	}
  	
  	private function typeCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_GENRE], "Le type CRD n'a pas été reconnu");
  	}
  	
  	private function categorieAnnexeNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_CATMVT], "La catégorie d'annexe n'a pas été reconnue");
  	}

  	private function mvtDetailsNotValidError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le mouvement attend des informations complementaires valides");
  	}
  	
  	private function typeAnnexeNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'annexe n'a pas été reconnu");
  	}

    private function retiraisonNotAllowedError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Aucune sortie cave ne permet la retiraison du contrat");
    }

    private function contratNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Le contrat n'a pas été trouvé");
    }

    private function productNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_PRODUIT], "Le produit n'a pas été trouvé");
    }

    private function droitsNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_DROITS], "Le type de droit n'a pas été trouvé");
    }

    private function complementProductWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_COMPLEMENT_PRODUIT], "Le complément produit n'est pas reconnu");
    }

    private function categorieMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT], "La catégorie de mouvement n'a pas été trouvée");
    }

    private function typeMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT], "Le type de mouvement n'a pas été trouvé");
    }

    private function categorieCrdMvtNotFoundError($num_ligne, $csvRow) {
    	return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CATEGORIE_KEY], "La catégorie de mouvement de CRD n'a pas été trouvée");
    }
    
    private function typeCrdMvtNotFoundError($num_ligne, $csvRow) {
    	return $this->createError($num_ligne, $csvRow[self::CSV_CRD_TYPE_KEY], "Le type de mouvement de CRD n'a pas été trouvé");
    }
    
    private function valeurMouvementNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_VOLUME], "La valeur doit être un nombre positif");
    }
    
    private function valeurCrdMouvementNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CRD_QUANTITE], "La valeur doit être un nombre entier positif");
    }
    
    private function valeurAnnexeNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La valeur doit être un nombre entier positif");
    }
    
    private function numeroRnaAnnexeNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NUMERODOCUMENT], "Le numéro de document doit être un nombre entier positif");
    }
    
    private function valeurStatistiqueNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La valeur doit être un nombre positif");
    }

    private function exportPaysNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_EXPORTPAYS], "Le pays d'export n'a pas été trouvé");
    }

    private function contratIDEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "L'identifiant du contrat ne peut pas être vide");
    }

    private function contratIDNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Le contrat n'a pas été trouvé");
    }

    private function observationsEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_VOLUME], "Les observations sont vides");
    }

    private function sucreWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La quantité de sucre est nulle ou possède un mauvais format");
    }

    private function typeDocumentWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le type de document d'annexe n'est pas connu");
    }

    private function annexesTypeMvtWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'enregistrement des " . $csvRow[self::CSV_ANNEXE_TYPEANNEXE] . " doit être 'début' ou 'fin'");
    }

    private function annexesNumeroDocumentError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le numéro de document ne peut pas être vide");
    }

    private function annexesNonApurementWrongDateError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION], "La date est vide ou mal formattée");
    }

    private function annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST], "Le numéro d'accises du destinataire est vide ou mal formatté");
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
