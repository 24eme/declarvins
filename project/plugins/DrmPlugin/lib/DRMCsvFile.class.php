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
  const CSV_COL_APPELLATION = 10;
  const CSV_COL_LIEU = 11;
  const CSV_COL_COULEUR = 12;
  const CSV_COL_CEPAGE = 13;
  const CSV_COL_MILLESIME = 14;
  const CSV_COL_LABELS = 15;
  const CSV_COL_MENTION = 16;
  const CSV_COL_CONTRAT_IDENTIFIANT = 17;
  const CSV_COL_CONTRAT_VOLUME = 18;
  const CSV_COL_DETAIL_TOTAL_DEBUT_MOIS = 17;
  const CSV_COL_DETAIL_ENTREES = 18;
  const CSV_COL_DETAIL_ENTREES_NOUVEAU = 19;
  const CSV_COL_DETAIL_ENTREES_REPLI = 20;
  const CSV_COL_DETAIL_ENTREES_DECLASSEMENT = 21;
  const CSV_COL_DETAIL_ENTREES_MOUVEMENT = 22;
  const CSV_COL_DETAIL_ENTREES_CRD = 23;
  const CSV_COL_DETAIL_SORTIES = 24;
  const CSV_COL_DETAIL_SORTIES_VRAC = 25;
  const CSV_COL_DETAIL_SORTIES_EXPORT = 26;
  const CSV_COL_DETAIL_SORTIES_FACTURES = 27;
  const CSV_COL_DETAIL_SORTIES_CRD = 28;
  const CSV_COL_DETAIL_SORTIES_CONSOMMATION = 29;
  const CSV_COL_DETAIL_SORTIES_PERTES = 30;
  const CSV_COL_DETAIL_SORTIES_DECLASSEMENT = 31;
  const CSV_COL_DETAIL_SORTIES_REPLI = 32;
  const CSV_COL_DETAIL_SORTIES_MOUVEMENT = 33;
  const CSV_COL_DETAIL_SORTIES_LIES = 34;
  const CSV_COL_DETAIL_TOTAL = 35;
  const CSV_COL_DETAIL_STOCKFIN_BLOQUE = 36;
  const CSV_COL_DETAIL_STOCKFIN_WARRANTE = 37;
  const CSV_COL_DETAIL_STOCKFIN_INSTANCE = 38;

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
      $detail->label_supplementaire = $line[self::CSV_COL_MENTION] * 1;
    return $detail;
  }

  private function parseContrat($line) {
    $detail = $this->getProduit($line);
    $detail->addVrac($line[self::CSV_COL_CONTRAT_IDENTIFIANT], $line[self::CSV_COL_CONTRAT_VOLUME]);
    $this->drm->update();
  }

  private function parseDetail($line) {
      $detail = $this->getProduit($line);
      $detail->entrees->nouveau = $line[self::CSV_COL_DETAIL_ENTREES_NOUVEAU] *1 ;
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
      $detail->sorties->lies = $line[self::CSV_COL_DETAIL_SORTIES_LIES] *1;
      $detail->stocks_fin->bloque = $line[self::CSV_COL_DETAIL_STOCKFIN_BLOQUE] *1;
      $detail->stocks_fin->warrante = $line[self::CSV_COL_DETAIL_STOCKFIN_WARRANTE] *1;
      $detail->stocks_fin->instance = $line[self::CSV_COL_DETAIL_STOCKFIN_INSTANCE] *1;
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