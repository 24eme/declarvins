<?php
class DRMExportCsvEdi extends DRMCsvEdi 
{
	protected $csv;

    public function __construct(DRM $drm) {
    	$this->csv = array();
        parent::__construct(null, $drm);
    }

    public function exportEDI($format = 'csv') 
    {
        if (!($this->drm instanceof InterfaceDRMExportable)) {
            new sfException('DRM must implements InterfaceDRMExportable');
        }
        $this->generateCsvEdi();
        $result = null;
        switch ($format) {
        	case 'csv':
        		$result = $this->getCsvFormat(); break;
        	case 'xml':
        		$result = $this->getXmlFormat(); break;
        	case 'debug':
        	default:
        		$result = $this->csv; break;
        }
        return $result;
    }
    
    protected function getCsvFormat() {
    	$csvFile = '';
    	foreach ($this->csv as $item) {
    		$csvFile .= implode(';', $item)."\n";
    	}
    	return $csvFile;
    }
    
    protected function getXmlFormat() {
    	return $this->getPartial('xml', array('csv' => $this->csv, 'drm' => $this->drm));
    }

    protected function generateCsvEdi() 
    {
    	$this->csv[] = array(
    		'#TYPE',
    		'PERIODE',
    		'IDENTIFIANT',
    		'ACCISE',
    		'TYPE DROITS / TYPE ANNEXE',
    		'CERTIFICATION / TYPE PRODUIT',
    		'GENRE / COULEUR CAPSULE',
    		'APPELLATION / CENTILITRAGE',
    		'MENTION',
    		'LIEU',
    		'COULEUR',
    		'CEPAGE',
    		'PRODUIT',
    		'CATEGORIE MOUVEMENT',
    		'TYPE MOUVEMENT',
    		'VOLUME / QUANTITE',
    		'PAYS EXPORT / DATE NON APUREMENT',
    		'NUMERO CONTRAT / NUMERO ACCISE DESTINATAIRE',
    		'NUMERO DOCUMENT',
    		'OBSERVATIONS'
    	);
    	$this->createMouvementsEdi();
    	$this->createContratsEdi();
    	$this->createCrdsEdi();
    	$this->createAnnexesEdi();
    }

    protected function getProduitCSV($produitDetail) 
    {
        $cepageConfig = $produitDetail->getCepage()->getConfig();
        return array(
        	DRMCsvEdi::CSV_CAVE_CERTIFICATION => $cepageConfig->getCertification()->getCode(),
        	DRMCsvEdi::CSV_CAVE_GENRE => $cepageConfig->getGenre()->getCode(),
        	DRMCsvEdi::CSV_CAVE_APPELLATION => $cepageConfig->getAppellation()->getCode(),
        	DRMCsvEdi::CSV_CAVE_MENTION => $cepageConfig->getMention()->getCode(),
        	DRMCsvEdi::CSV_CAVE_LIEU => $cepageConfig->getLieu()->getCode(),
        	DRMCsvEdi::CSV_CAVE_COULEUR => $cepageConfig->getCouleur()->getCode(),
        	DRMCsvEdi::CSV_CAVE_CEPAGE => $cepageConfig->getCepage()->getCode(),
        	DRMCsvEdi::CSV_CAVE_PRODUIT => trim($produitDetail->getLibelle()));
    }

    protected function createMouvementsEdi() 
    {
        foreach ($this->drm->getExportableProduits() as $hashProduit => $produitDetail) {
            $champs = $this->drm->getExportableCategoriesMouvements();
            foreach ($champs as $champ) {
            	if (!$produitDetail->exist($champ)) {
            		continue;
            	}
            	$mvt = (!is_object($produitDetail->get($champ)) && !is_array($produitDetail->get($champ)))? array($champ => $produitDetail->get($champ)) : $produitDetail->get($champ);
            	foreach ($mvt as $key => $val) {
            		if ($val) {
            			$droit = (preg_match('/^acq_/', $key))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
            			// Il faudrait ameliorer ce code de DRMESDetails
            			if ($val instanceof DRMESDetails) {
            				foreach ($val as $detailKey => $detailValue) {
            					if ($detailVol = $detailValue->getVolume()) {
            						$complement = $detailValue->getIdentifiant();
            						$numeroDoc = ($detailValue->numero_document) ? $detailValue->numero_document : '';
            						if ($key == 'export_details') {
            							$pays = $this->countryList[$detailValue->getIdentifiant()];
            							$this->addCsvLigne(DRMCsvEdi::TYPE_CAVE, $this->merge(array(
            								DRMCsvEdi::CSV_CAVE_TYPE_DROITS => $droit,
            								DRMCsvEdi::CSV_CAVE_CATEGORIE_MOUVEMENT => $champ,
            								DRMCsvEdi::CSV_CAVE_TYPE_MOUVEMENT => $this->drm->getExportableLibelleMvt($key),
            								DRMCsvEdi::CSV_CAVE_VOLUME => $detailVol,
            								DRMCsvEdi::CSV_CAVE_EXPORTPAYS => $pays,
            								DRMCsvEdi::CSV_CAVE_NUMERODOCUMENT => $numeroDoc), $this->getProduitCSV($produitDetail))
            							);
            						}
            						if ($key == 'vrac_details') {
            							$numeroVrac = str_replace('VRAC-', '', $detailValue->getIdentifiant());
            							$this->addCsvLigne(DRMCsvEdi::TYPE_CAVE, $this->merge(array(
            								DRMCsvEdi::CSV_CAVE_TYPE_DROITS => $droit,
            								DRMCsvEdi::CSV_CAVE_CATEGORIE_MOUVEMENT => $champ,
            								DRMCsvEdi::CSV_CAVE_TYPE_MOUVEMENT => $this->drm->getExportableLibelleMvt($key),
            								DRMCsvEdi::CSV_CAVE_VOLUME => $detailVol,
            								DRMCsvEdi::CSV_CAVE_CONTRATID => $numeroVrac,
            								DRMCsvEdi::CSV_CAVE_NUMERODOCUMENT => $numeroDoc), $this->getProduitCSV($produitDetail))
            							);
            						}
            					}
            				}
            			} else {
	            			$this->addCsvLigne(DRMCsvEdi::TYPE_CAVE, $this->merge(array(
	            				DRMCsvEdi::CSV_CAVE_TYPE_DROITS => $droit,
	            				DRMCsvEdi::CSV_CAVE_CATEGORIE_MOUVEMENT => $champ,
	            				DRMCsvEdi::CSV_CAVE_TYPE_MOUVEMENT => $this->drm->getExportableLibelleMvt($key),
	            				DRMCsvEdi::CSV_CAVE_VOLUME => $val), $this->getProduitCSV($produitDetail))
	            			);
            			}
            		}
            	}
            }
        }
    }

    protected function createContratsEdi()
    {
    	$produitsDetails = $this->drm->getExportableProduits();
    	$contrats = $this->drm->getExportableVracs();
    	foreach ($produitsDetails as $hashProduit => $produitDetail) {
    		if (isset($contrats[$hashProduit])) {
    			foreach ($contrats[$hashProduit] as $contrat) {
    				$this->addCsvLigne(DRMCsvEdi::TYPE_CONTRAT, $this->merge(array(
    					DRMCsvEdi::CSV_CONTRAT_TYPE_DROITS => DRMCsvEdi::TYPE_DROITS_SUSPENDUS,
    					DRMCsvEdi::CSV_CONTRAT_VOLUME => $contrat[DRMCsvEdi::CSV_CONTRAT_VOLUME],
    					DRMCsvEdi::CSV_CONTRAT_CONTRATID => $contrat[DRMCsvEdi::CSV_CONTRAT_CONTRATID]), $this->getProduitCSV($produitDetail))
    				);
    			}
    		}
    	}
    }

    protected function createCrdsEdi() 
    {
        foreach ($this->drm->getExportableCrds() as $key => $crd) {
            foreach ($crd as $crdDetail) {
            	$droit = (preg_match('/acq/i', $crdDetail[DRMCsvEdi::CSV_CRD_LIBELLE]))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
            	$this->addCsvLigne(DRMCsvEdi::TYPE_CRD, array(
            		DRMCsvEdi::CSV_CRD_TYPE_DROITS => $droit,
            		DRMCsvEdi::CSV_CRD_GENRE => $crdDetail[DRMCsvEdi::CSV_CRD_GENRE],
            		DRMCsvEdi::CSV_CRD_COULEUR => $crdDetail[DRMCsvEdi::CSV_CRD_COULEUR],
            		DRMCsvEdi::CSV_CRD_CENTILITRAGE => $crdDetail[DRMCsvEdi::CSV_CRD_CENTILITRAGE],
            		DRMCsvEdi::CSV_CRD_LIBELLE => $crdDetail[DRMCsvEdi::CSV_CRD_LIBELLE],
            		DRMCsvEdi::CSV_CRD_CATEGORIE_KEY => $crdDetail[DRMCsvEdi::CSV_CRD_CATEGORIE_KEY],
            		DRMCsvEdi::CSV_CRD_TYPE_KEY => $crdDetail[DRMCsvEdi::CSV_CRD_TYPE_KEY],
            		DRMCsvEdi::CSV_CRD_QUANTITE => $crdDetail[DRMCsvEdi::CSV_CRD_QUANTITE])
            	);
            }
        }
    }

    protected function createAnnexesEdi() 
    {
        if ($documents = $this->drm->getExportableDocuments()) {
        	foreach ($documents as $type => $document) {
        		foreach ($document as $doc) {
        			$this->addCsvLigne(DRMCsvEdi::TYPE_ANNEXE, array(
        				DRMCsvEdi::CSV_ANNEXE_TYPEANNEXE => DRMCsvEdi::TYPE_ANNEXE_DOCUMENT,
        				DRMCsvEdi::CSV_ANNEXE_CATMVT => $type,
        				DRMCsvEdi::CSV_ANNEXE_TYPEMVT => $doc[DRMCsvEdi::CSV_ANNEXE_TYPEMVT],
        				DRMCsvEdi::CSV_ANNEXE_QUANTITE => $doc[DRMCsvEdi::CSV_ANNEXE_QUANTITE])
        			);
        		}
        	}
        }
        if ($rnas = $this->drm->getExportableRna()) {
        	foreach ($rnas as $rna) {
        		$this->addCsvLigne(DRMCsvEdi::TYPE_ANNEXE, array(
        			DRMCsvEdi::CSV_ANNEXE_TYPEANNEXE => DRMCsvEdi::TYPE_ANNEXE_NONAPUREMENT,
        			DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTDATEEMISSION => $rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTDATEEMISSION],
        			DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST => $rna[DRMCsvEdi::CSV_ANNEXE_NONAPUREMENTACCISEDEST],
        			DRMCsvEdi::CSV_ANNEXE_NUMERODOCUMENT => $rna[DRMCsvEdi::CSV_ANNEXE_NUMERODOCUMENT])
        		);
        	}
        }
        if ($sucres = $this->drm->getExportableSucre()) {
        	foreach ($sucres as $key => $produits) {
        		foreach ($produits as $produit) {
        			$this->addCsvLigne(DRMCsvEdi::TYPE_ANNEXE, $this->merge(array(
        				DRMCsvEdi::CSV_CAVE_TYPE_DROITS => DRMCsvEdi::TYPE_ANNEXE_SUCRE,
        				DRMCsvEdi::CSV_ANNEXE_QUANTITE => $produit->get($key)), $this->getProduitCSV($produit))
        			);
        		}
        	}
        }
        if ($statistiques = $this->drm->getExportableStatistiquesEuropeennes()) {
        	foreach ($statistiques as $stat => $vol) {
        		$this->addCsvLigne(DRMCsvEdi::TYPE_ANNEXE, array(
        			DRMCsvEdi::CSV_ANNEXE_TYPEANNEXE => DRMCsvEdi::TYPE_ANNEXE_STATISTIQUES,
        			DRMCsvEdi::CSV_ANNEXE_CATMVT => $stat,
        			DRMCsvEdi::CSV_ANNEXE_QUANTITE => $vol)
        		);
        	}
        }
        if ($observations = $this->drm->getExportableObservations()) {
            $this->addCsvLigne(DRMCsvEdi::TYPE_ANNEXE, array(
        		DRMCsvEdi::CSV_ANNEXE_TYPEANNEXE => DRMCsvEdi::TYPE_ANNEXE_OBSERVATIONS,
            	DRMCsvEdi::CSV_ANNEXE_OBSERVATION => $observations)
            );
        }
    }
    
    protected function getCsvLigne($type) {
    	$ligne = array();
    	$informations = $this->drm->getExportableDeclarantInformations();
    	for ($i=0; $i<DRMCsvEdi::CSV_NB_TOTAL_COL; $i++) {
    		$ligne[$i] = null;
    	}
    	$ligne[DRMCsvEdi::CSV_TYPE] = $type;
    	$ligne[DRMCsvEdi::CSV_PERIODE] = $informations[DRMCsvEdi::CSV_PERIODE];
    	$ligne[DRMCsvEdi::CSV_IDENTIFIANT] = $informations[DRMCsvEdi::CSV_IDENTIFIANT];
    	$ligne[DRMCsvEdi::CSV_NUMACCISE] = $informations[DRMCsvEdi::CSV_NUMACCISE];
    	return $ligne;
    }
    
    protected function addCsvLigne($type, array $ligne) 
    {
    	$this->csv[] = $this->merge($ligne, $this->getCsvLigne($type));
    }
    
    protected function merge(array $arr1, array $arr2) 
    {
    	$arr = $arr1 + $arr2;
    	ksort($arr);
    	return $arr;
    }


    protected static function getPartial($partial, $vars = null)
    {
    	return sfContext::getInstance()->getController()->getAction('edi_export', 'main')->getPartial('edi_export/' . $partial, $vars);
    }

}
