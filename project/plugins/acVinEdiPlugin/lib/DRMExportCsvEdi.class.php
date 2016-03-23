<?php
class DRMExportCsvEdi extends DRMCsvEdi 
{
	protected $configuration;

    public function __construct(DRM $drm = null) 
    {
    	$this->configuration = ConfigurationEdi::getInstance('drm');
        parent::__construct(null, $drm);
    }

    public function exportEDI() 
    {
        if (!$this->drm) {
            new sfException('Absence de DRM');
        }
        if (!($this->drm instanceof InterfaceDRMExportable)) {
            new sfException('DRM must implements InterfaceDRMExportable');
        }
        $header = $this->createHeaderEdi();
        $body = $this->createBodyEdi();
        return $header . $body;
    }

    private function createHeaderEdi() 
    {
        return "#TYPE;PERIODE;IDENTIFIANT;ACCISE;TYPE DROITS;CERTIFICATION / TYPE PRODUIT; GENRE / COULEUR CAPSULE; APPELLATION / CENTILITRAGE;MENTION;LIEU;COULEUR;CEPAGE;PRODUIT;CATEGORIE MOUVEMENT / TYPE DOCUMENT;TYPE MOUVEMENT;VOLUME / QUANTITE;PAYS EXPORT / DATE NON APUREMENT;NUMERO CONTRAT / NUMERO ACCISE DESTINATAIRE NON APUREMENT;NUMERO DOCUMENT ACCOMPAGNEMENT;OBSERVATIONS\n";
    }

    private function createBodyEdi() 
    {
        $body = $this->createMouvementsEdi();
        $body .= $this->createContratsEdi();
        $body.= $this->createCrdsEdi();
        //$body.= $this->createAnnexesEdi();
        return $body;
    }

    public function getProduitCSV($produitDetail) 
    {
        $cepageConfig = $produitDetail->getCepage()->getConfig();

        $certification = $cepageConfig->getCertification()->getCode();
        $genre = $cepageConfig->getGenre()->getCode();
        $appellation = $cepageConfig->getAppellation()->getCode();
        $mention = $cepageConfig->getMention()->getCode();
        $lieu = $cepageConfig->getLieu()->getCode();
        $couleur = $cepageConfig->getCouleur()->getCode();
        $cepage = $cepageConfig->getCepage()->getCode();
        $libelle = $produitDetail->getLibelle();

        return $certification . ";" . $genre . ";" . $appellation . ";" . $mention . ";" . $lieu . ";" . $couleur . ";" . $cepage . ";" . $libelle;
    }

    private function getLibelleDetail($keyDetail) 
    {
        if ($keyDetail == 'vrac_details') {
            return 'contrat';
        }
        return str_replace('_details', '', $keyDetail);
    }

    private function createMouvementsEdi() 
    {
        $mouvementsEdi = "";
        $produitsDetails = $this->drm->getExportableProduits();
        $hasAcquittes = $this->drm->hasExportableProduitsAcquittes();
        $debutLigne = self::TYPE_CAVE . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";";

        foreach ($produitsDetails as $hashProduit => $produitDetail) {
        	if ($produitDetail->total_debut_mois) {
        		$mouvementsEdi.= $debutLigne . DRMCsvEdi::TYPE_DROITS_SUSPENDUS.";" .$this->getProduitCSV($produitDetail) . ";" . "total_debut_mois;;" . (($produitDetail->total_debut_mois)? $produitDetail->total_debut_mois : 0) . ";\n";
        	}
            if ($hasAcquittes && $produitDetail->acq_total_debut_mois) {
            	$mouvementsEdi.= $debutLigne . DRMCsvEdi::TYPE_DROITS_ACQUITTES.";" .$this->getProduitCSV($produitDetail) . ";" . "total_debut_mois;;" . (($produitDetail->acq_total_debut_mois)? $produitDetail->acq_total_debut_mois : 0) . ";\n";
            }
            foreach ($produitDetail->stocks_debut as $stockdebut_key => $stockdebutValue) {
                if ($stockdebutValue) {
                	$droit = (preg_match('/^acq_/', $stockdebut_key))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
                	$stockdebut_key = str_replace('acq_', '', $stockdebut_key);
                    $mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "stocks_debut;" . $stockdebut_key . ";" . $stockdebutValue . ";\n";
                }
            }
            foreach ($produitDetail->entrees as $entreekey => $entreeValue) {
                if ($entreeValue) {
                	$droit = (preg_match('/^acq_/', $entreekey))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
                	$entreekey = str_replace('acq_', '', $entreekey);
                    $mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "entrees;" . $entreekey . ";" . $entreeValue . ";\n";
                }
            }
            foreach ($produitDetail->sorties as $sortiekey => $sortieValue) {
                if ($sortieValue) {
                	$droit = (preg_match('/^acq_/', $sortiekey))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
                	$sortiekey = str_replace('acq_', '', $sortiekey);
                    if ($sortieValue instanceof DRMESDetails) {
                        foreach ($sortieValue as $sortieDetailKey => $sortieDetailValue) {
                            if ($sortieDetailValue->getVolume()) {
                                $complement = $sortieDetailValue->getIdentifiant();
                               
                                $numero_doc = ($sortieDetailValue->numero_document) ? $sortieDetailValue->numero_document : '';
                                if ($sortiekey == 'export_details') {
                                    $pays = $this->countryList[$sortieDetailValue->getIdentifiant()];
                                    $mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "sorties;" . $this->getLibelleDetail($sortiekey) . ";" . $sortieDetailValue->getVolume() . ";" . $pays . ";;" . $numero_doc . "\n";
                                }
                                if ($sortiekey == 'vrac_details') {
                                    $numero_vrac = str_replace('VRAC-', '', $sortieDetailValue->getIdentifiant());
                                    $mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "sorties;" . $this->getLibelleDetail($sortiekey) . ";" . $sortieDetailValue->getVolume() . ";;" . $numero_vrac . ";" . $numero_doc . "\n";
                                }
                            }
                        }
                    } else {
                    	$mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "sorties;" . $sortiekey . ";" . $sortieValue . ";\n";
                    }
                }
            }
            foreach ($produitDetail->stocks_fin as $stockfin_key => $stockfinValue) {
                if ($stockfinValue) {
                	$droit = (preg_match('/^acq_/', $stockfin_key))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
                	$stockfin_key = str_replace('acq_', '', $stockfin_key);
                    $mouvementsEdi.= $debutLigne . $droit.";".$this->getProduitCSV($produitDetail) . ";" . "stocks_fin;" . $stockfin_key . ";" . $stockfinValue . ";\n";
                }
            }
        }
        return $mouvementsEdi;
    }

    private function createContratsEdi()
    {

    	$contratsEdi = "";
    	$produitsDetails = $this->drm->getExportableProduits();
    	$contrats = $this->drm->getExportableVracs();
    	$debutLigne = self::TYPE_CONTRAT . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";";
    	foreach ($produitsDetails as $hashProduit => $produitDetail) {
    		if (isset($contrats[$hashProduit])) {
    			foreach ($contrats[$hashProduit] as $contrat) {
    				$contratsEdi.= $debutLigne . DRMCsvEdi::TYPE_DROITS_SUSPENDUS. ";". $this->getProduitCSV($produitDetail) . ";;;" . $contrat[DRMCsvEdi::CSV_CONTRAT_VOLUME] .";;" . $contrat[DRMCsvEdi::CSV_CONTRAT_CONTRATID] . ";\n";
    			}
    		}
    	}
    	return $contratsEdi;
    }

    private function createCrdsEdi() 
    {
        $crdsEdi = "";
        $debutLigne = self::TYPE_CRD . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";";
        foreach ($this->drm->getExportableCrds() as $key => $crd) {
            foreach ($crd as $crdDetail) {
            	$droit = (preg_match('/acq/i', $crdDetail[DRMCsvEdi::CSV_CRD_LIBELLE]))? DRMCsvEdi::TYPE_DROITS_ACQUITTES : DRMCsvEdi::TYPE_DROITS_SUSPENDUS;
            	$crdsEdi.= $debutLigne . $droit.";".$crdDetail[DRMCsvEdi::CSV_CRD_GENRE] . ";" . $crdDetail[DRMCsvEdi::CSV_CRD_COULEUR] . ";" . $crdDetail[DRMCsvEdi::CSV_CRD_CENTILITRAGE] . ";;;;;" . $crdDetail[DRMCsvEdi::CSV_CRD_LIBELLE] . ";" . $crdDetail[DRMCsvEdi::CSV_CRD_CATEGORIE_KEY] . ";" . $crdDetail[DRMCsvEdi::CSV_CRD_TYPE_KEY] . ";" . $crdDetail[DRMCsvEdi::CSV_CRD_QUANTITE] . ";\n";
            }
        }
        return $crdsEdi;
    }

    private function createAnnexesEdi() 
    {
        $annexesEdi = "";
        $debutLigneAnnexe = self::TYPE_ANNEXE . ";" . $this->drm->periode . ";" . $this->drm->identifiant . ";" . $this->drm->declarant->no_accises . ";;;;;;;;;";

        foreach ($this->drm->documents_annexes as $typeDoc => $numsDoc) {
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";debut;;;;" . $numsDoc->debut . "\n";
            $annexesEdi.=$debutLigneAnnexe . $typeDoc . ";fin;;;;" . $numsDoc->fin . "\n";
        }

        foreach ($this->drm->releve_non_apurement as $non_apurement) {
            $annexesEdi.=$debutLigneAnnexe . self::TYPE_ANNEXE_NONAPUREMENT . ";;;".$non_apurement->date_emission.";" . $non_apurement->numero_accise . ";" . $non_apurement->numero_document . "\n";
        }
        if ($this->drm->quantite_sucre) {

            $annexesEdi.=$debutLigneAnnexe .  self::TYPE_ANNEXE_SUCRE . ";;" . $this->drm->quantite_sucre . ";\n";
        }
        if ($this->drm->observations) {

            $annexesEdi.=$debutLigneAnnexe . self::TYPE_ANNEXE_OBSERVATIONS . ";;;;;;" . $this->drm->observations . "\n";
        }

        return $annexesEdi;
    }

}
