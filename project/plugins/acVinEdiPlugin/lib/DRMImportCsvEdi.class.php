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
    protected $complements = array();
    protected $drmPrecedente = null;
    protected $cacheConfProduit = array();

    public function __construct($file, DRM $drm = null)
    {
        $this->configuration = ConfigurationClient::getCurrent();
        $this->permettedValues = array(
            DRMCsvEdi::TYPE_CAVE => array(
                    DRMCsvEdi::CSV_CAVE_COMPLEMENT_PRODUIT => array_keys($this->configuration->getLabels())
            )
        );
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

    private function getCacheKeyFromData($datas) {
        return $datas[self::CSV_CAVE_CERTIFICATION].
            	$this->formatCacheKey($datas[self::CSV_CAVE_GENRE]).
            	$this->formatCacheKey($datas[self::CSV_CAVE_APPELLATION]).
            	$this->formatCacheKey($datas[self::CSV_CAVE_MENTION]).
            	$this->formatCacheKey($datas[self::CSV_CAVE_LIEU]).
            	$this->formatCacheKey($datas[self::CSV_CAVE_COULEUR]).
            	$this->formatCacheKey($datas[self::CSV_CAVE_CEPAGE]).
                $this->formatCacheKey($datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]).
                $this->formatCacheKey($datas[self::CSV_CAVE_PRODUIT]).
                $this->formatCacheKey($datas[self::CSV_CAVE_TYPE_DROITS]);
    }

    private function formatCacheKey($value) {
        $value = $this->formatPermissifCool($value);
        $value = KeyInflector::slugify($value);

        return $value;
    }

    public function createCacheProduits() {
        $numLigne = 0;
        $this->cache = array();
        if($this->drm->canSetStockDebutMois()){
            $this->drm->remove("declaration");
            $this->drm->add("declaration");
        }
        $rows = $this->getDocRows();
        $produits = [];
        foreach ($rows as $row) {
            if (strtoupper($row[self::CSV_TYPE]) != self::TYPE_CAVE) {
                continue;
            }
            if (!isset($produits[$this->getCacheKeyFromData($row)])) {
                $produits[$this->getCacheKeyFromData($row)] = $row;
            }
            if (strpos(strtolower(trim($row[self::CSV_CAVE_CATEGORIE_MOUVEMENT])), 'stock') !== false && strtolower(trim($row[self::CSV_CAVE_TYPE_MOUVEMENT])) == 'total_debut_mois') {
                $produits[$this->getCacheKeyFromData($row)] = '';
            }
        }
        foreach($produits as $produit) {
            if ($produit) {
                $produit[self::CSV_CAVE_CATEGORIE_MOUVEMENT] = 'stocks';
                $produit[self::CSV_CAVE_TYPE_MOUVEMENT] = 'total_debut_mois';
                $produit[self::CSV_CAVE_VOLUME] = 0;
                $rows[] = $produit;
            }
        }
        foreach ($rows as $datas) {
            $numLigne++;
            if (strtoupper($datas[self::CSV_TYPE]) != self::TYPE_CAVE) {
                continue;
            }
            if (strpos(strtolower(trim($datas[self::CSV_CAVE_CATEGORIE_MOUVEMENT])), 'stock') === false) {
                continue;
            }
            if (strtolower($datas[self::CSV_CAVE_TYPE_MOUVEMENT]) != 'total_debut_mois') {
                continue;
            }
            $complement_libelle = null;
            $complement_hash = null;
            $label = null;
    		$libelle = $this->getKey($datas[self::CSV_CAVE_PRODUIT]);
    		$configurationProduit = null;
    		$isAutre = false;
            $idDouane = $this->getIdDouane($datas);
            if (isset($this->cacheConfProduit[$this->getCacheKeyFromData($datas)])) {
                $configurationProduit = $this->cacheConfProduit[$this->getCacheKeyFromData($datas)];
            }
    		if (!$configurationProduit && $idDouane) {
              $lp = '';
              $s = strpos($libelle, '(');
              $e = strpos($libelle, ')');
              $codeDouaneDansLibelle = (substr(trim($libelle), -1) == ')');
              if ($codeDouaneDansLibelle && $s !== false && $e !== false) {
                $lp = trim(substr($libelle, 0, $s));
              }
    		  $configurationProduit = $this->configuration->identifyProduct(null, "$lp($idDouane)");
    		}
    		if (!$configurationProduit) {
    			$configurationProduit = $this->configuration->getConfigurationProduit($this->getHashProduit($datas));
    		}
    		if (!$configurationProduit) {
    		    $configurationProduit = $this->configuration->getConfigurationProduitByLibelle($libelle);
    		}

    		if((!$configurationProduit) && $idDouane && $libelle) {
    		    $default_produit_hash = $this->configuration->getDefaultProduitHash($idDouane);
    		    $configurationProduit = $this->configuration->getConfigurationProduit($default_produit_hash);
    		    $isAutre = true;
    		}

        	if (!$configurationProduit) {
        		$this->csvDoc->addErreur($this->productNotFoundError($numLigne, $datas));
        		continue;
      		}
            if (!isset($this->cacheConfProduit[$this->getCacheKeyFromData($datas)])) {
                $this->cacheConfProduit[$this->getCacheKeyFromData($datas)] = $configurationProduit;
            }

    		$hash = str_replace('/declaration', 'declaration', $configurationProduit->getHash());

      		$libellePerso = null;
      		$libelleConfig = ConfigurationProduitClient::getInstance()->format($configurationProduit->getLibelles(), array(), "%g% %a% %l% %co% %ce%");

            $s = strpos($libelle, '(');
            $e = strpos($libelle, ')');
            $codeDouaneDansLibelle = (substr(trim($libelle), -1) == ')');
            if ($codeDouaneDansLibelle && $s !== false && $e !== false) {
            	$libellePerso = trim(substr($libelle, 0, $s));
            } elseif ($libelle && trim($libelle) != trim($libelleConfig)) {
                $libellePerso = trim($libelle);
            }

            if ($libellePerso) {
              $complement_libelle = $libellePerso;
            } else {
              $complement_libelle = null;
            }

            if ($datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]) {
                if (isset($this->permettedValues[self::TYPE_CAVE]) && isset($this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT])) {
                    $complement = strtoupper(trim($datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]));
                    if (in_array($complement, $this->permettedValues[self::TYPE_CAVE][self::CSV_CAVE_COMPLEMENT_PRODUIT])) {
                        $label = $complement;
                        $libellePerso = trim($libelle);
                        if (!$libellePerso) {
                            $libellePerso = trim($libelleConfig);
                        }
                        $complement_libelle = $libellePerso.' '.$label;
                    } else {
                        $complement_libelle = trim($datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]);
                    }
                }
            }

            $produit = $this->drm->getProduitByIdDouane($hash, ($idDouane)? $idDouane : $configurationProduit->getIdentifiantDouane(), $label, $complement_libelle);


            if (!$produit) {
                $produits = $this->drm->getProduitsByIdDouaneAndStockDebut(($idDouane)? $idDouane : $configurationProduit->getIdentifiantDouane(), $complement_libelle, $label, $this->floatize($datas[self::CSV_CAVE_VOLUME]));
                if (count($produits) > 1) {
                    if ($this->drm->canSetStockDebutMois()) {
                        $produits = [];
                    } else {
                        throw new sfException('ambiguité identification produit (trop de volume identiques) pour '.(($idDouane)? $idDouane : $configurationProduit->getIdentifiantDouane()));
                    }
                }
                if (count($produits) == 1) {
                    $produit = $produits[0];
                }
            }

            if (!$produit) {
                $produit = $this->drm->addProduit($hash, $label, $complement_libelle);
      		}

            if ($libellePerso) {
                $produit->libelle = $libellePerso;
            }

      		if ($complement_libelle && !$label) {
                $l = $libellePerso;
                if ($libellePerso != $complement_libelle) {
                  $l .= ' '.$complement_libelle;
                }
                $produit->libelle = ($libellePerso) ? trim($l) : trim($datas[self::CSV_CAVE_COMPLEMENT_PRODUIT]);
      		}

    		if ($complement_libelle && $label) {
              $l = $libellePerso;
              if ($libellePerso != $complement_libelle) {
                $l .= ' '.$complement_libelle;
              }
              $produit->libelle = $complement_libelle;
    		}

      		if ($idDouane && ($isAutre||$idDouane != $produit->getIdentifiantDouane())) {
      		    $produit->add('inao', $idDouane);
      		}

            $this->cache[$this->getCacheKeyFromData($datas)] = $produit;
        }
        if($this->drm->canSetStockDebutMois()) {
            if ($this->drmPrecedente) {
                if ($produitsReserve = $this->drmPrecedente->getProduitsReserveInterpro()) {
                    foreach ($produitsReserve as $produitReserve) {
                        if ($this->drm->exist($produitReserve->getHash())) {
                            $produitAddReserve = $this->drm->get($produitReserve->getHash());
                            $produitAddReserve->add('reserve_interpro', $produitReserve->getReserveInterpro());
                            if ($produitReserve->hasCapaciteCommercialisation()) {
                                $produitAddReserve->add('reserve_interpro_capacite_commercialisation', $produitReserve->getCapaciteCommercialisation());
                            }
                            if ($produitReserve->hasSuiviSortiesChais()) {
                                $vol = (substr($this->drm->periode, -2) == 12)? 0 : round($produitReserve->getSuiviSortiesChais() + $produitReserve->getVolumeSortieChai(), 2);
                                $produitAddReserve->add('reserve_interpro_suivi_sorties_chais', $vol);
                            }
                        }
                    }
                }
            }
        }
    }

    public function getProduitFromCache($datas) {
        if (!isset($this->cache[$this->getCacheKeyFromData($datas)])) {
            return null;
        }
        return $this->cache[$this->getCacheKeyFromData($datas)];
    }

    public function importCsv()
    {
        $numLigne = 0;
    	foreach ($this->getDocRows() as $csvRow) {
            if (preg_match('/^(...)?#/', $csvRow[self::CSV_TYPE])) {
                continue;
            }
    		if ($numLigne == 0) {
    			$this->importDRM($csvRow);
    			if ($this->csvDoc->hasErreurs()) {
    				break;
    			}
    			$this->createCacheProduits();
    		}
            $numLigne++;
    		switch(strtoupper($csvRow[self::CSV_TYPE])) {
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
    	foreach ($this->complements as $l => $row) {
    		$this->importCave($l, $row, true);
    	}
    	$this->drm->restoreLibelle();
    	if ($this->csvDoc->hasErreurs()) {
    		$this->csvDoc->setStatut(self::STATUT_ERREUR);
    		$this->csvDoc->save();
    		return;
    	}
    }

    private function importDRM($datas)
  	{
  		$this->drm->setImportablePeriode($datas[self::CSV_PERIODE]);

  		$identifiant = strtoupper(trim($datas[self::CSV_IDENTIFIANT]));
  		$ea = trim($datas[self::CSV_NUMACCISE]);
  		$siretCvi = null;
  		if (preg_match('/([a-zA-Z0-9\ \-\_]*)\(([a-zA-Z0-9\ \-\_]*)\)/', $identifiant, $result)) {
  			$identifiant = trim($result[1]);
  			$siretCvi = trim($result[2]);
  		}
  		$result = $this->drm->setImportableIdentifiant($identifiant, $ea, $siretCvi);
  		if (!$result) {
  			$this->csvDoc->addErreur($this->etablissementNotFoundError());
  			return;
  		}
  		$this->drmPrecedente = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->drm->identifiant, DRMClient::getInstance()->getPeriodePrecedente($this->drm->periode));
    }

    protected function isComplement($datas)
    {
    	return (preg_match('/^compl.+ment/i', $datas[self::CSV_CAVE_CATEGORIE_MOUVEMENT]))? true : false;
    }

    private function importCave($numLigne, $datas, $complements = false)
  	{

        if ($this->isComplement($datas) && !$complements) {
    		$this->complements[$numLigne] = $datas;
    		return;
    	}

        $produit = $this->getProduitFromCache($datas);
        if (!$produit) {
            return;
        }

        $droits = $this->matchDroits(trim($datas[self::CSV_CAVE_TYPE_DROITS]));
        if (!in_array($droits, array(self::TYPE_DROITS_SUSPENDUS, self::TYPE_DROITS_ACQUITTES))) {
            $this->csvDoc->addErreur($this->droitsNotFoundError($numLigne, $datas));
            return;
        }

  		$categorieMvt = strtolower($datas[self::CSV_CAVE_CATEGORIE_MOUVEMENT]);
  		$typeMvt = $this->drm->getImportableLibelleMvt($droits, $categorieMvt, strtolower($datas[self::CSV_CAVE_TYPE_MOUVEMENT]));
  		$valeur = $this->floatize($datas[self::CSV_CAVE_VOLUME]);

  		if ($droits == DRMCsvEdi::TYPE_DROITS_ACQUITTES && !$this->drm->droits_acquittes && $valeur > 0) {
  		    $this->drm->setHasDroitsAcquittes(1);
  		}

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
		  			$this->csvDoc->addErreur($this->categorieOrTypeMouvementNotFoundError($numLigne, $datas));
		  			return;
  				} else {
  					$categorieMvt = null;
  				}
	  		}
	  		if ($categorieMvt && !is_object($produit->get($categorieMvt))) {
		  		$this->csvDoc->addErreur($this->categorieMouvementNotFoundError($numLigne, $datas));
		  		return;
	  		}
  			if ($categorieMvt && !$produit->get($categorieMvt)->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->typeMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		} elseif(!$categorieMvt && !$produit->exist($typeMvt)) {
	  			$this->csvDoc->addErreur($this->typeMouvementNotFoundError($numLigne, $datas));
	  			return;
	  		}
  		}
  		$vracIsComplement = false;
        if ((!$categorieMvt && $typeMvt == 'vrac') || ($typeMvt == 'vrac' && $datas[self::CSV_CAVE_CONTRATID] && preg_match('/[0-9]+/', $datas[self::CSV_CAVE_CONTRATID]))) {
  		    if (!$categorieMvt) {
  		        $vracIsComplement = true;
  		    }
  			$numContrat = $datas[self::CSV_CAVE_CONTRATID];
  			if (!$produit->hasSortieVrac() && $vracIsComplement) {
  				$this->csvDoc->addErreur($this->retiraisonNotAllowedError($numLigne, $datas));
  				return;
  			}
            if (!isset($this->contrats[$produit->getHash()])) {
                $this->contrats[$produit->getHash()] = $produit->getContratsVrac();
            }
  			$exist = false;
  			foreach ($this->contrats[$produit->getHash()] as $contrat) {
  				if ($numContrat == $contrat->getNumeroContrat() && $contrat->valide->statut == VracClient::STATUS_CONTRAT_NONSOLDE) {
  					$exist = true;
  					if ($contrat->version) {
  						$numContrat .= '-'.$contrat->version;
  					}
  					break;
  				}
  			}
  			if (!$exist && $produit->getCertification()->getKey() != 'VINSSANSIG') {
  				$this->csvDoc->addErreur($this->contratNotFoundError($numLigne, $datas));
  				return;
  			} elseif (!is_numeric($valeur) || $valeur < 0) {
  				$this->csvDoc->addErreur($this->valeurMouvementNotValidError($numLigne, $datas));
  				return;
  			}
  			if ($exist) {
  			   $produit->addVrac($numContrat, round($this->floatize($valeur), 2));
  			}

  		}
  		if ($vracIsComplement) {
  		    return;
  		} elseif (!$categorieMvt && preg_match('/^observation/i', $typeMvt)) {
  			if (!$valeur) {
  				$this->csvDoc->addErreur($this->observationsEmptyError($numLigne, $datas));
  				return;
  			}
  			$produit->setImportableObservations($valeur);
  		} elseif (!$categorieMvt && preg_match('/^pr.+mix$/i', $typeMvt)) {
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
            if(!$this->drm->canSetStockDebutMois() && $typeMvt == 'total_debut_mois' && $this->floatize($valeur) > 0 && $this->drmPrecedente && !$this->drmPrecedente->exist($produit->getHash())) {
                $this->csvDoc->addErreur($this->productDuplicateError($numLigne, $datas));
            }
	  		$mvt = ($categorieMvt)? $produit->getOrAdd($categorieMvt) : $produit;
	  		$old = (in_array(str_replace('acq_', '', $typeMvt), array('total_debut_mois', 'total', 'tav')))? 0 : floatval($mvt->getOrAdd($typeMvt));
	  		$mvt->add($typeMvt, round(($old + $this->floatize($valeur)), 5));
	  		$result = $this->drm->setImportableMvtDetails($typeMvt, $mvt, $datas);
	  		if (!$result) {
	  			$this->csvDoc->addErreur($this->mvtDetailsNotValidError($numLigne, $datas));
	  			return;
	  		}
  		}
    }

    private function importCrd($numLigne, $datas)
  	{
  		$categorie = strtoupper($datas[self::CSV_CRD_COULEUR]);
  		$type = strtoupper($datas[self::CSV_CRD_TYPE_DROITS]);
  		$centilisation = strtoupper($datas[self::CSV_CRD_CENTILITRAGE]);

  		$categorieCrd = strtolower($datas[self::CSV_CRD_CATEGORIE_KEY]);
  		$typeCrd = strtolower($datas[self::CSV_CRD_TYPE_KEY]);
        $typeCrd = str_replace('utilisations', 'utilisees', $typeCrd);
        $typeCrd = str_replace('destructions', 'detruites', $typeCrd);
        $typeCrd = str_replace('manquants', 'manquantes', $typeCrd);

        if(!preg_match('/s$/', $typeCrd)) {
            $typeCrd .= 's';
        }

  		$valeur = $datas[self::CSV_CRD_QUANTITE];

  		if (!$this->configuration->isCategorieCrdAccepted($categorie)) {
  			$this->csvDoc->addErreur($this->categorieCrdNotFoundError($numLigne, $datas));
  			return;
  		}
      $typeDroit = $this->configuration->isTypeCrdAccepted($type);
  		if (!$typeDroit) {
  			$this->csvDoc->addErreur($this->typeCrdNotFoundError($numLigne, $datas));
  			return;
  		}

  		if (!$this->configuration->isCentilisationCrdAccepted($centilisation)||$centilisation == 'AUTRE') {
  			$isBib = null;
  			if (preg_match('/^(BIB|CL)_([0-9]+)/i', $centilisation, $m)) {
  				$crd = $this->drm->addCrd($categorie, $typeDroit, 'AUTRE', $m[2], ($m[1] == 'BIB'));
  			} else {
  				$this->csvDoc->addErreur($this->centilisationCrdNotFoundError($numLigne, $datas));
  				return;
  			}
  		} else {
  			$crd = $this->drm->addCrd($categorie, $typeDroit, $centilisation, null, null);
  		}

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

      $mvt = ($categorieCrd)? $crd->getOrAdd($categorieCrd) : $crd;

      if ($this->isComplement($datas)) {
        $mvt->add($typeCrd, $valeur);
      } else {
    		if (!is_numeric($valeur) || $valeur < 0 || intval($valeur) != $valeur) {
  	  		$this->csvDoc->addErreur($this->valeurCrdMouvementNotValidError($numLigne, $datas));
  	  		return;
    		}

    		$old = (in_array($typeCrd , array('total_debut_mois', 'total_fin_mois')))? 0 : intval($mvt->getOrAdd($typeCrd));
    		$mvt->add($typeCrd, ($old + intval($valeur)));
      }

    }

    private function importAnnexe($numLigne, $datas)
  	{
    	switch (strtolower($datas[self::CSV_ANNEXE_CATMVT])) {
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


    	if ($accises && !preg_match('/^[A-Za-z]{2}[0-9A-Za-z]{11}$/', $accises)) {
    		$this->csvDoc->addErreur($this->annexesNonApurementWrongNumAcciseError($numLigne, $datas));
    		return;
    	}

    	if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $date)) {
    		$this->csvDoc->addErreur($this->annexesNonApurementWrongDateError($numLigne, $datas));
			return;
    	}

    	$this->drm->setImportableRna($numero, $accises, $date);
    }

    private function importDocument($numLigne, $datas)
    {
    	$declaratif = $this->drm->getImportableDeclaratif();

    	$categorie = strtolower($datas[self::CSV_ANNEXE_CATMVT]);
    	$type = strtolower($datas[self::CSV_ANNEXE_TYPEMVT]);
    	$valeur = $datas[self::CSV_ANNEXE_QUANTITE];

    	if (!$categorie || !$declaratif->exist($categorie)) {
    		$this->csvDoc->addErreur($this->categorieAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}
    	if (!$declaratif->get($categorie)->exist($type)) {
    		$this->csvDoc->addErreur($this->typeAnnexeNotFoundError($numLigne, $datas));
    		return;
    	}

  		$mvt = $declaratif->getOrAdd($categorie);
  		$mvt->add($type, (is_numeric($valeur))? $valeur * 1 : $valeur);
    }

    private function importStatistiques($numLigne, $datas)
    {

    	$declaratif = $this->drm->getImportableDeclaratif();

    	$categorie = strtolower($datas[self::CSV_ANNEXE_CATMVT]);
    	$type = strtolower($datas[self::CSV_ANNEXE_TYPEMVT]);
    	$valeur = $datas[self::CSV_ANNEXE_QUANTITE] * 1;

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
    	$old = floatval($mvt->getOrAdd($type));
    	$mvt->add($type, round(($old + $this->floatize($valeur)), 2));
    }

    private function checkCSVIntegrity() {
        $ligne_num = 0;
        $periodes = array();
        $accises = array();
        $identifiants = array();
        foreach ($this->getDocRows() as $csvRow) {
            if (preg_match('/^(...)?#/', $csvRow[self::CSV_TYPE])) {
                continue;
            }
            $ligne_num++;
            if (!in_array(strtoupper($csvRow[self::CSV_TYPE]), self::$permitted_types)) {
                $this->csvDoc->addErreur($this->createWrongFormatTypeError($ligne_num, $csvRow));
            }
            if (!preg_match('/^[0-9]{6}$/', $csvRow[self::CSV_PERIODE])) {
                $this->csvDoc->addErreur($this->createWrongFormatPeriodeError($ligne_num, $csvRow));
            } else {
            	$periodes[$csvRow[self::CSV_PERIODE]] = 1;
            }
            if ($csvRow[self::CSV_NUMACCISE] && !preg_match('/^FR[a-zA-Z0-9]{11}$/', $csvRow[self::CSV_NUMACCISE])) {
                $this->csvDoc->addErreur($this->createWrongFormatNumAcciseError($ligne_num, $csvRow));
            } else {
            	$accises[$csvRow[self::CSV_NUMACCISE]] = 1;
            }
            $identifiants[$csvRow[self::CSV_IDENTIFIANT]] = 1;
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

    private function formatPermissifCool($value) {

        $value =  preg_replace("/e?s?$/i", "", $value);

        return $value;
    }

    private function matchDroits($d) {
    	if (preg_match('/suspendu/i', $d)) {

    		return self::TYPE_DROITS_SUSPENDUS;
    	}
    	if (preg_match('/acquitt.+/i', $d)) {

    		return self::TYPE_DROITS_ACQUITTES;
    	}

    	return $d;
    }

    private function createMultiPeriodeError() {
        return $this->createError(null, 'DRM', "Import limité à une seule période", 'error_multi_periode');
    }
    private function createMultiAcciseError() {
        return $this->createError(null, 'DRM', "Import limité à un seul numéro d'EA", 'error_multi_ea');
    }
    private function createMultiIdentifiantError() {
        return $this->createError(null, 'DRM', "Import limité à un seul établissement", 'error_multi_etablissement');
    }

    private function etablissementNotFoundError() {
    	return $this->createError(null, 'DRM', "Impossible d'identifier l'établissement", 'error_notfound_etablissement');
    }

    private function createWrongFormatTypeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_TYPE]), "Choix possible type : " . implode(', ', self::$permitted_types), 'error_format_type');
    }

    private function createWrongFormatPeriodeError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_PERIODE]), "Format période : AAAAMM", 'error_format_periode');
    }

    private function createWrongFormatNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, KeyInflector::slugify($csvRow[self::CSV_NUMACCISE]), "Format numéro d'accises non valide", 'error_format_ea');
    }

  	private function centilisationCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CENTILITRAGE], "La centilisation CRD n'a pas été reconnue", 'error_notfound_crdcentilisation');
  	}

  	private function categorieCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_TYPE_DROITS], "La catégorie fiscale CRD n'a pas été reconnue", 'error_notfound_crdcategorie');
  	}

  	private function typeCrdNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_CRD_GENRE], "Le type CRD n'a pas été reconnu", 'error_notfound_crdtype');
  	}

  	private function categorieAnnexeNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_CATMVT], "La catégorie d'annexe n'a pas été reconnue", 'error_notfound_annexecategorie');
  	}

  	private function mvtDetailsNotValidError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le mouvement attend des informations complementaires valides", 'error_notvalid_mvtinfos');
  	}

  	private function typeAnnexeNotFoundError($num_ligne, $csvRow) {
  		return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'annexe n'a pas été reconnu", 'error_notfound_annexetype');
  	}

    private function retiraisonNotAllowedError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Aucune sortie cave ne permet la retiraison du contrat", 'error_notallow_retiraison');
    }

    private function contratNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CONTRATID], "Le contrat n'a pas été trouvé", 'error_notfound_contrat');
    }

    private function productNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_PRODUIT], "Le produit n'a pas été trouvé", 'error_notfound_produit');
    }

    private function productDuplicateError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_PRODUIT], "Ce produit a un stock début de mois > 0 alors qu'il n'était pas présent dans la DRM du mois précédent. Il peut aussi s'agir d'un problème dans la reconnaissance du produit", 'error_duplicate_produit');
    }

    private function droitsNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_DROITS], "Le type de droit n'a pas été trouvé", 'error_notfound_droitstype');
    }

    private function complementProductWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_COMPLEMENT_PRODUIT], "Le complément produit n'est pas reconnu", 'error_notfound_produitcomplement');
    }

    private function categorieMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT], "La catégorie de mouvement n'a pas été trouvée", 'error_notfound_mvtcategorie');
    }

    private function typeMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_TYPE_MOUVEMENT], "Le type de mouvement n'a pas été trouvé", 'error_notfound_mvttype');
    }

    private function categorieOrTypeMouvementNotFoundError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_CATEGORIE_MOUVEMENT].' / '.$csvRow[self::CSV_CAVE_TYPE_MOUVEMENT], "La catégorie ou le type de mouvement n'a pas été trouvée", 'error_notfound_mvtcategorietype');
    }

    private function categorieCrdMvtNotFoundError($num_ligne, $csvRow) {
    	return $this->createError($num_ligne, $csvRow[self::CSV_CRD_CATEGORIE_KEY], "La catégorie de mouvement de CRD n'a pas été trouvée", 'error_notfound_crdcategoriemvt');
    }

    private function typeCrdMvtNotFoundError($num_ligne, $csvRow) {
    	return $this->createError($num_ligne, $csvRow[self::CSV_CRD_TYPE_KEY], "Le type de mouvement de CRD n'a pas été trouvé", 'error_notfound_crdtypemvt');
    }

    private function valeurMouvementNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_VOLUME], "La valeur doit être un nombre positif", 'error_notvalid_mvtvolume');
    }

    private function valeurCrdMouvementNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CRD_QUANTITE], "La valeur doit être un nombre entier positif", 'error_notvalid_crdquantite');
    }

    private function valeurAnnexeNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La valeur doit être un nombre entier positif", 'error_notvalid_annexequantite');
    }

    private function numeroRnaAnnexeNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NUMERODOCUMENT], "Le numéro de document doit être un nombre entier positif", 'error_notvalid_annexerna');
    }

    private function valeurStatistiqueNotValidError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_QUANTITE], "La valeur doit être un nombre positif", 'error_notvalid_annexestatsvolume');
    }

    private function observationsEmptyError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_CAVE_VOLUME], "Les observations sont vides", 'error_empty_observations');
    }

    private function typeDocumentWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le type de document d'annexe n'est pas connu", 'error_notfound_annexetypedoc');
    }

    private function annexesTypeMvtWrongFormatError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEMVT], "Le type d'enregistrement des " . $csvRow[self::CSV_ANNEXE_TYPEANNEXE] . " doit être 'début' ou 'fin'", 'error_notvalid_annexetypedoc');
    }

    private function annexesNumeroDocumentError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_TYPEANNEXE], "Le numéro de document ne peut pas être vide", 'error_empty_annexetypedoc');
    }

    private function annexesNonApurementWrongDateError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTDATEEMISSION], "La date est vide ou mal formattée", 'error_notvalid_annexernadate');
    }

    private function annexesNonApurementWrongNumAcciseError($num_ligne, $csvRow) {
        return $this->createError($num_ligne, $csvRow[self::CSV_ANNEXE_NONAPUREMENTACCISEDEST], "Le numéro d'accises du destinataire est vide ou mal formatté", 'error_notvalid_annexernaea');
    }

    private function createError($num_ligne, $erreur_csv, $raison, $id = null) {
        $error = new stdClass();
        $error->num_ligne = $num_ligne;
        $error->erreur_csv = $erreur_csv;
        $error->raison = $raison;
        $error->id = $id;
        return $error;
    }

    private function getIdDouane($datas)
    {
    	$certification = trim(str_replace(array('(', ')'), '', $datas[self::CSV_CAVE_CERTIFICATION]));
    	if (
    			$certification &&
    			!$this->getKey($datas[self::CSV_CAVE_GENRE]) &&
    			!$this->getKey($datas[self::CSV_CAVE_APPELLATION]) &&
    			!$this->getKey($datas[self::CSV_CAVE_MENTION]) &&
    			!$this->getKey($datas[self::CSV_CAVE_LIEU]) &&
    			!$this->couleurKeyToCode($datas[self::CSV_CAVE_COULEUR], false) &&
    			!$this->getKey($datas[self::CSV_CAVE_CEPAGE])
    		) {
    		return $certification;
    	}
      $libelle = $this->getKey($datas[self::CSV_CAVE_PRODUIT]);
      $libelleDouane = null;
      $s = strpos($libelle, '(');
      $e = strpos($libelle, ')');
      $codeDouaneDansLibelle = (substr(trim($libelle), -1) == ')');
      if ($codeDouaneDansLibelle && $s !== false && $e !== false) {
        $libelleDouane = substr($libelle, $s+1, $e-$s-1);
      }
      return $libelleDouane;
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
  		return self::floatizeVal($value);
  	}

  	public static function floatizeVal($value)
  	{
  	    if ($value === null) {
  	        return null;
  	    }
  	    $value = str_replace(',', '.', $value);
  	    return (is_numeric($value))? floatval($value) : str_replace('.', ',', $value);
  	}

}
