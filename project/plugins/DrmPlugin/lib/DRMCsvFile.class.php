<?php

class DRMCsvFile extends CsvFile 
{

  const NOEUD_TEMPORAIRE = 'TMP';
  const DEFAULT_KEY = 'DEFAUT';
  const CSV_COL_TYPE = 0;
  const CSV_COL_IDENTIFIANT_DECLARANT = 1;
  const CSV_COL_NOM_DECLARANT = 2;
  const CSV_COL_ANNEE = 3;
  const CSV_COL_MOIS = 4;
  const CSV_COL_RECTIFICATIVE = 5;
  const CSV_COL_ANNEE_PRECEDENTE = 6;
  const CSV_COL_MOIS_PRECEDENTE = 7;
  const CSV_COL_RECTIFICATIVE_PRECEDENTE = 8;
  const CSV_COL_CERTIFICATION = 9;
  const CSV_COL_CERTIFICATION_CODE = 10;
  const CSV_COL_GENRE = 11;
  const CSV_COL_GENRE_CODE = 12;
  const CSV_COL_APPELLATION = 13;
  const CSV_COL_APPELLATION_CODE = 14;
  const CSV_COL_LIEU = 15;
  const CSV_COL_LIEU_CODE = 16;
  const CSV_COL_COULEUR = 17;
  const CSV_COL_COULEUR_CODE = 18;
  const CSV_COL_CEPAGE = 19;
  const CSV_COL_CEPAGE_CODE = 20;
  const CSV_COL_MILLESIME = 21;
  const CSV_COL_MILLESIME_CODE = 22;
  const CSV_COL_LABELS = 23;
  const CSV_COL_LABELS_CODE = 24;
  const CSV_COL_MENTION = 25;
  const CSV_COL_CONTRAT_IDENTIFIANT = 26;
  const CSV_COL_CONTRAT_VOLUME = 27;
  const CSV_COL_DETAIL_TOTAL_DEBUT_MOIS = 26;
  const CSV_COL_DETAIL_ENTREES = 27;
  const CSV_COL_DETAIL_ENTREES_ACHAT = 28;
  const CSV_COL_DETAIL_ENTREES_RECOLTE = 29;
  const CSV_COL_DETAIL_ENTREES_REPLI = 30;
  const CSV_COL_DETAIL_ENTREES_DECLASSEMENT = 31;
  const CSV_COL_DETAIL_ENTREES_MOUVEMENT = 32;
  const CSV_COL_DETAIL_ENTREES_CRD = 33;
  const CSV_COL_DETAIL_SORTIES = 34;
  const CSV_COL_DETAIL_SORTIES_VRAC = 35;
  const CSV_COL_DETAIL_SORTIES_EXPORT = 36;
  const CSV_COL_DETAIL_SORTIES_FACTURES = 37;
  const CSV_COL_DETAIL_SORTIES_CRD = 38;
  const CSV_COL_DETAIL_SORTIES_CONSOMMATION = 39;
  const CSV_COL_DETAIL_SORTIES_PERTES = 40;
  const CSV_COL_DETAIL_SORTIES_DECLASSEMENT = 41;
  const CSV_COL_DETAIL_SORTIES_REPLI = 42;
  const CSV_COL_DETAIL_SORTIES_MOUVEMENT = 43;
  const CSV_COL_DETAIL_SORTIES_DISTILLATION = 44;
  const CSV_COL_DETAIL_SORTIES_LIES = 45;
  const CSV_COL_DETAIL_TOTAL = 46;
  const CSV_COL_DETAIL_STOCKFIN_BLOQUE = 47;
  const CSV_COL_DETAIL_STOCKFIN_WARRANTE = 48;
  const CSV_COL_DETAIL_STOCKFIN_INSTANCE = 49;
  const CSV_COL_DETAIL_STOCKFIN_COMMERCIALISABLE = 49;

  public function __construct(DRM $drm) {
    $this->csvdata = array();
    foreach ($drm->getDetails() as $d) {
      $line = array();
      $line[self::CSV_COL_TYPE] = 'DETAIL';
      $line[self::CSV_COL_IDENTIFIANT_DECLARANT] = $d->getDocument()->getIdentifiant();
      $line[self::CSV_COL_NOM_DECLARANT] = $d->getDocument()->getDeclarant()->getNom();
      $line[self::CSV_COL_ANNEE] = $d->getDocument()->getAnnee();
      $line[self::CSV_COL_MOIS] = $d->getDocument()->getMois();
      $line[self::CSV_COL_RECTIFICATIVE] = $d->getDocument()->getRectificative();
      $line[self::CSV_COL_ANNEE_PRECEDENTE] = $d->getDocument()->getPrecedente()->getAnnee();
      $line[self::CSV_COL_MOIS_PRECEDENTE] = $d->getDocument()->getPrecedente()->getMois();
      $line[self::CSV_COL_RECTIFICATIVE_PRECEDENTE] = $d->getDocument()->getPrecedente()->getRectificative();
      $line[self::CSV_COL_CERTIFICATION] = $d->getCertification()->getLibelle();
      $line[self::CSV_COL_CERTIFICATION_CODE] = $d->getCertification()->getCode();
      $line[self::CSV_COL_GENRE] = $d->getGenre()->getLibelle();
      $line[self::CSV_COL_GENRE_CODE] = $d->getGenre()->getCode();
      $line[self::CSV_COL_APPELLATION] = $d->getAppellation()->getLibelle();
      $line[self::CSV_COL_APPELLATION_CODE] = $d->getAppellation()->getCode();
      $line[self::CSV_COL_LIEU] = $d->getLieu()->getLibelle();
      $line[self::CSV_COL_LIEU_CODE] = $d->getLieu()->getCode();
      $line[self::CSV_COL_COULEUR] = $d->getCouleur()->getLibelle();
      $line[self::CSV_COL_COULEUR_CODE] = $d->getCouleur()->getCode();
      $line[self::CSV_COL_CEPAGE] = $d->getCepage()->getLibelle();
      $line[self::CSV_COL_CEPAGE_CODE] = $d->getCepage()->getCode();
      $line[self::CSV_COL_MILLESIME] = $d->getMillesime()->getLibelle();
      $line[self::CSV_COL_MILLESIME_CODE] = $d->getMillesime()->getCode();
      $line[self::CSV_COL_LABELS] = $d->getLabelLibellesString();
      $line[self::CSV_COL_LABELS_CODE] = $d->getLabelKeyString();
      $line[self::CSV_COL_MENTION] = $d->label_supplementaire;
      $line[self::CSV_COL_DETAIL_TOTAL_DEBUT_MOIS] = $d->total_debut_mois;
      $line[self::CSV_COL_DETAIL_ENTREES] = $d->total_entrees;
      $line[self::CSV_COL_DETAIL_ENTREES_ACHAT] = $d->entrees->achat;
      $line[self::CSV_COL_DETAIL_ENTREES_RECOLTE] = $d->entrees->recolte;
      $line[self::CSV_COL_DETAIL_ENTREES_REPLI] = $d->entrees->repli;
      $line[self::CSV_COL_DETAIL_ENTREES_DECLASSEMENT] = $d->entrees->declassement;
      $line[self::CSV_COL_DETAIL_ENTREES_MOUVEMENT] = $d->entrees->mouvement;
      $line[self::CSV_COL_DETAIL_ENTREES_CRD] = $d->entrees->crd;
      $line[self::CSV_COL_DETAIL_SORTIES] = $d->total_sorties;
      $line[self::CSV_COL_DETAIL_SORTIES_VRAC] = $d->sorties->vrac;
      $line[self::CSV_COL_DETAIL_SORTIES_EXPORT] = $d->sorties->export;
      $line[self::CSV_COL_DETAIL_SORTIES_FACTURES] = $d->sorties->factures;
      $line[self::CSV_COL_DETAIL_SORTIES_CRD] = $d->sorties->crd;
      $line[self::CSV_COL_DETAIL_SORTIES_CONSOMMATION] = $d->sorties->consommation;
      $line[self::CSV_COL_DETAIL_SORTIES_PERTES] = $d->sorties->pertes;
      $line[self::CSV_COL_DETAIL_SORTIES_DECLASSEMENT] = $d->sorties->declassement;
      $line[self::CSV_COL_DETAIL_SORTIES_REPLI] = $d->sorties->repli;
      $line[self::CSV_COL_DETAIL_SORTIES_MOUVEMENT] = $d->sorties->mouvement;
      $line[self::CSV_COL_DETAIL_SORTIES_DISTILLATION] = $d->sorties->distillation;
      $line[self::CSV_COL_DETAIL_SORTIES_LIES] = $d->sorties->lies;
      $line[self::CSV_COL_DETAIL_TOTAL] = $d->total;
      $line[self::CSV_COL_DETAIL_STOCKFIN_BLOQUE] = $d->stocks_fin->bloque;
      $line[self::CSV_COL_DETAIL_STOCKFIN_WARRANTE] = $d->stocks_fin->warrante;
      $line[self::CSV_COL_DETAIL_STOCKFIN_INSTANCE] = $d->stocks_fin->instance;
      $line[self::CSV_COL_DETAIL_STOCKFIN_COMMERCIALISABLE] = $d->stocks_fin->commercialisable;
      $this->csvdata[] = $line;
    }
  }

  private function verifyCsvLine($detail, $line) {
    if ($line[self::CSV_COL_DETAIL_ENTREES]*1 != $detail->total_entrees)
      throw new sfException("la somme des entrees (".$detail->total_entrees.") n'est pas en accord avec les informations du csv (".$line[14].")");
    if ($line[self::CSV_COL_DETAIL_SORTIES]*1 != $detail->total_sorties)
      throw new sfException("la somme des sorties n'est pas en accord avec les informations du csv");
    if ($line[self::CSV_COL_DETAIL_TOTAL]*1 != $detail->total)
      throw new sfException("le total n'est pas en accord avec les informations du csv");
    if ($line[self::CSV_COL_DETAIL_TOTAL_DEBUT_MOIS]*1 != $detail->total_debut_mois*1)
      throw new sfException("le total début de mois n'est pas en accord avec les informations historiques");
  }

  private function getProduit($line) {
    if($this->drm->getAnnee() != $line[self::CSV_COL_ANNEE])
      throw new sfException("Incoherence dans l'année de la DRM");
    if($this->drm->getMois() != $line[self::CSV_COL_MOIS])
      throw new sfException("Incoherence dans le mois de la DRM");
    if($this->drm->identifiant != $line[self::CSV_COL_IDENTIFIANT_DECLARANT])
      throw new sfException("Incoherence dans l'identifiant de l'établissement DRM");
    
    $hash = $this->config->identifyProduct($line[self::CSV_COL_CERTIFICATION], 
					   $line[self::CSV_COL_APPELLATION], 
					   $line[self::CSV_COL_LIEU], 
					   $line[self::CSV_COL_COULEUR], 
					   $line[self::CSV_COL_CEPAGE], 
					   $line[self::CSV_COL_MILLESIME]);
    $detail = $this->drm->addProduit($hash, $this->config->identifyLabels($line[self::CSV_COL_LABELS]))->getDetail();
    if ($line[self::CSV_COL_MENTION])
      $detail->label_supplementaire = $line[self::CSV_COL_MENTION];
    return $detail;
  }

  private function parseContrat($line) {
    $detail = $this->getProduit($line);
    $detail->addVrac($line[self::CSV_COL_CONTRAT_IDENTIFIANT], $line[self::CSV_COL_CONTRAT_VOLUME]);
    $this->drm->update();
  }

  private function parseDetail($line) {
      $detail = $this->getProduit($line);
      $detail->entrees->achat = $line[self::CSV_COL_DETAIL_ENTREES_ACHAT] *1 ;
      $detail->entrees->recolte = $line[self::CSV_COL_DETAIL_ENTREES_RECOLTE] *1 ;
      $detail->entrees->repli = $line[self::CSV_COL_DETAIL_ENTREES_REPLI] * 1 ;
      $detail->entrees->declassement = $line[self::CSV_COL_DETAIL_ENTREES_DECLASSEMENT] *1;
      $detail->entrees->mouvement = $line[self::CSV_COL_DETAIL_ENTREES_MOUVEMENT] *1;
      $detail->entrees->crd = $line[self::CSV_COL_DETAIL_ENTREES_CRD] *1;
      $detail->sorties->vrac = $line[self::CSV_COL_DETAIL_SORTIES_VRAC] *1;
      $detail->sorties->export = $line[self::CSV_COL_DETAIL_SORTIES_EXPORT] *1;
      $detail->sorties->factures = $line[self::CSV_COL_DETAIL_SORTIES_FACTURES] *1;
      $detail->sorties->crd = $line[self::CSV_COL_DETAIL_SORTIES_CRD] *1;
      $detail->sorties->consommation = $line[self::CSV_COL_DETAIL_SORTIES_CONSOMMATION] *1;
      $detail->sorties->pertes = $line[self::CSV_COL_DETAIL_SORTIES_PERTES] *1;
      $detail->sorties->declassement = $line[self::CSV_COL_DETAIL_SORTIES_DECLASSEMENT] *1;
      $detail->sorties->repli = $line[self::CSV_COL_DETAIL_SORTIES_REPLI] *1;
      $detail->sorties->mouvement = $line[self::CSV_COL_DETAIL_SORTIES_MOUVEMENT] *1;
      $detail->sorties->distillation = $line[self::CSV_COL_DETAIL_SORTIES_DISTILLATION] *1;
      $detail->sorties->lies = $line[self::CSV_COL_DETAIL_SORTIES_LIES] *1;
      $detail->stocks_fin->bloque = $line[self::CSV_COL_DETAIL_STOCKFIN_BLOQUE] *1;
      $detail->stocks_fin->warrante = $line[self::CSV_COL_DETAIL_STOCKFIN_WARRANTE] *1;
      $detail->stocks_fin->instance = $line[self::CSV_COL_DETAIL_STOCKFIN_INSTANCE] *1;
      $detail->stocks_fin->commercialisable = $line[self::CSV_COL_DETAIL_STOCKFIN_COMMERCIALISABLE] *1;
      $this->drm->update();
      $this->verifyCsvLine($detail, $line);
  }

  public function importDRM($etablissement) {
    $this->config = ConfigurationClient::getCurrent();
    $this->drm = null;
    $this->errors = array();
    $this->numline = 0;
    try {
      foreach ($this->getCsv() as $line) {
	$this->numline++;
	if (!$this->drm)
	  $this->drm = DRMClient::getInstance()->retrieveOrCreateByIdentifiantAndCampagne($etablissement, $line[self::CSV_COL_ANNEE], $line[self::CSV_COL_MOIS]);
	switch($line[self::CSV_COL_TYPE]) {
	case 'DETAIL':
	  $this->parseDetail($line);
	  break;
	case 'CONTRAT':
	  $this->parseContrat($line);
	  break;
	}
      }
    }catch(sfException $e){
      $this->errors[] = array('line'=> $this->numline, 'message'=>$e->getMessage());
    }

    $validator = new DRMValidation($this->drm);

    if ($validator->hasErrors()) {
      foreach($validator->getErrors() as $err) {
	$this->errors[] = array('message' => $err->getMessage());
      }
    }

    if (count($this->errors)) {
      throw new sfException('errors');
    }
    return $this->drm;
  }

  public function getErrors() {
    return $this->errors;
  }
}