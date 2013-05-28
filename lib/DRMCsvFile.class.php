<?php

class DRMCsvFile extends CsvFile 
{

  const NOEUD_TEMPORAIRE = 'TMP';
  const DEFAULT_KEY = 'DEFAUT';
  
  protected $errors;
  
  public function __construct($file = null, $ignore_first_if_comment = 1) {
  	parent::__construct($file, $ignore_first_if_comment);
  	$this->errors = array();
  }

  public static function datize($str) 
  {
  	if (!$str) {
  		return null;
  	}
    if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}/', $str)) {
      return $str;
    }
    if (preg_match('/^\d{4}-\d{2}-\d{2}([^T]|$)/', $str)) {
      return $str.'T00:00:00Z';
    }
    if (preg_match('/\//', $str)) {
      $str = preg_replace('/(\d{2})\/(\d{2})\/(\d{4})/', '\3-\2-\1', $str);
      return $str.'T00:00:00Z' ;
    }
    if (preg_match('/2012$/', $str)) {
      $str = preg_replace('/(\d{2})(\d{2})2012/', '2012-\2-\1', $str);
      return $str.'T00:00:00Z' ;
    }
    throw new sfException("Wrong date format $str");
  }

  public static function createFromArray($array) 
  {
    $csv = new DRMCsvFile();
    $csv->csvdata = $array;
    return $csv;
  }

  public static function createFromDRM(DRM $drm) 
  {
    $csv = new DRMCsvFile();
    $csv->csvdata = array();
    foreach ($drm->getDetails() as $detail) {
	    $line = array();
	    $line[DRMDateView::VALUE_TYPE] = 'DETAIL';
		$line[DRMDateView::VALUE_IDENTIFIANT_DECLARANT] = $drm->getIdentifiant();
		$line[DRMDateView::VALUE_RAISON_SOCIALE_DECLARANT] = $drm->declarant->raison_sociale;
		$line[DRMDateView::VALUE_ANNEE] = DRMClient::getInstance()->getAnnee($drm->periode);
		$line[DRMDateView::VALUE_MOIS] = DRMClient::getInstance()->getMois($drm->periode);
		$line[DRMDateView::VALUE_VERSION] = $drm->version;
		$line[DRMDateView::VALUE_ANNEE_PRECEDENTE] = DRMClient::getInstance()->getAnneeByDRMId($drm->precedente);
		$line[DRMDateView::VALUE_MOIS_PRECEDENTE] = DRMClient::getInstance()->getMoisByDRMId($drm->precedente);
		$line[DRMDateView::VALUE_VERSION_PRECEDENTE] = DRMClient::getInstance()->getVersionByDRMId($drm->precedente);
		$line[DRMDateView::VALUE_CERTIFICATION] = $detail->getCertification()->getKey();
		$line[DRMDateView::VALUE_CERTIFICATION_CODE] = $detail->getCertification()->getKey();
		$line[DRMDateView::VALUE_GENRE] = $detail->getGenre()->getLibelle();
		$line[DRMDateView::VALUE_GENRE_CODE] = $detail->getGenre()->getCode();
		$line[DRMDateView::VALUE_APPELLATION] = $detail->getAppellation()->getLibelle();
		$line[DRMDateView::VALUE_APPELLATION_CODE] = $detail->getAppellation()->getCode();
		$line[DRMDateView::VALUE_LIEU] = $detail->getLieu()->getLibelle();
		$line[DRMDateView::VALUE_LIEU_CODE] = $detail->getLieu()->getCode();
		$line[DRMDateView::VALUE_COULEUR] = $detail->getCouleur()->getLibelle();
		$line[DRMDateView::VALUE_COULEUR_CODE] = $detail->getCouleur()->getCode();
		$line[DRMDateView::VALUE_CEPAGE] = $detail->getCepage()->getLibelle();
		$line[DRMDateView::VALUE_CEPAGE_CODE] = $detail->getCepage()->getCode();
		$line[DRMDateView::VALUE_MILLESIME] = $detail->millesime;
		$line[DRMDateView::VALUE_MILLESIME_CODE] = null;
		$line[DRMDateView::VALUE_LABELS] = $detail->getLabelsLibelle("%la%", "|");
		$line[DRMDateView::VALUE_LABELS_CODE] = $detail->getLabelKeyString();
		$line[DRMDateView::VALUE_MENTION_EXTRA] = $detail->label_supplementaire;
		$line[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS] = $detail->total_debut_mois;
		$line[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE] = $detail->stocks_debut->bloque;
		$line[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE] = $detail->stocks_debut->warrante;
		$line[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE] = $detail->stocks_debut->instance;
		$line[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE] = $detail->stocks_debut->commercialisable;
		$line[DRMDateView::VALUE_DETAIL_ENTREES] = $detail->total_entrees;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT] = $detail->entrees->achat;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE] = $detail->entrees->recolte;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_REPLI] = $detail->entrees->repli;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT] = $detail->entrees->declassement;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT] = $detail->entrees->mouvement;
		$line[DRMDateView::VALUE_DETAIL_ENTREES_CRD] = $detail->entrees->crd;
		$line[DRMDateView::VALUE_DETAIL_SORTIES] = $detail->total_sorties;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] = $detail->sorties->vrac;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT] = $detail->sorties->export;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES] = $detail->sorties->factures;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_CRD] = $detail->sorties->crd;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION] = $detail->sorties->consommation;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_PERTES] = $detail->sorties->pertes;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT] = $detail->sorties->declassement;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_REPLI] = $detail->sorties->repli;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT] = $detail->sorties->mouvement;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION] = $detail->sorties->distillation;
		$line[DRMDateView::VALUE_DETAIL_SORTIES_LIES] = $detail->sorties->lies;
		$line[DRMDateView::VALUE_DETAIL_TOTAL] = $detail->total;
		$line[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE] = $detail->stocks_fin->bloque;
		$line[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE] = $detail->stocks_fin->warrante;
		$line[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE] = $detail->stocks_fin->instance;
		$line[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE] = $detail->stocks_fin->commercialisable;
		$line[DRMDateView::VALUE_DATEDESAISIE] = $drm->valide->date_saisie;
		$line[DRMDateView::VALUE_DATEDESIGNATURE] = $drm->valide->date_signee;
		$line[DRMDateView::VALUE_MODEDESAISIE] = $drm->mode_de_saisie;
		$line[DRMDateView::VALUE_DETAIL_CVO_CODE] = $detail->cvo->code;
		$line[DRMDateView::VALUE_DETAIL_CVO_TAUX] = $detail->cvo->taux;
		$line[DRMDateView::VALUE_DETAIL_CVO_VOLUME] = $detail->cvo->volume_taxable;
		$line[DRMDateView::VALUE_DETAIL_CVO_MONTANT] = $detail->cvo->taux * $detail->cvo->volume_taxable;
		$line[DRMDateView::VALUE_CAMPAGNE] = str_replace('-', '', $drm->campagne);
		$line[DRMDateView::VALUE_IDDRM] = $drm->identifiant_drm_historique;
		$line[DRMDateView::VALUE_IDIVSE] = $drm->identifiant_ivse;
    	$csv->csvdata[] = $line;
    }
    return $csv;
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
  		return ($key)? $key : Configuration::DEFAULT_KEY;
  	} elseif (!$key) {
  		throw new Exception('La clé "'.$key.'" n\'est pas valide');
  	} else {
  		return $key;
  	}
  }
  
  private function getHashProduit($line) 
  {
  	/*
  	 * ATTENTION CODE COULEUR
  	 */
  	$hash = 'declaration/certifications/'.$this->getKey($line[DRMDateView::VALUE_CERTIFICATION_CODE]).
                '/genres/'.$this->getKey($line[DRMDateView::VALUE_GENRE_CODE], true).
                '/appellations/'.$this->getKey($line[DRMDateView::VALUE_APPELLATION_CODE], true).
                '/mentions/'.Configuration::DEFAULT_KEY.
                '/lieux/'.$this->getKey($line[DRMDateView::VALUE_LIEU_CODE], true).
                '/couleurs/'.strtolower($line[DRMDateView::VALUE_COULEUR_CODE]).
                '/cepages/'.$this->getKey($line[DRMDateView::VALUE_CEPAGE_CODE], true);
    return $hash;
  }

  private function parseContrat($detail, $line) 
  {
    $detail->addVrac($line[DRMDateView::VALUE_CONTRAT_NUMERO], $line[DRMDateView::VALUE_CONTRAT_VOLUME]);
  }

  private function parseDetail($detail, $line) 
  {
      $detail->total_debut_mois = ($line[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS])? $line[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS] * 1 : 0;
      $detail->stocks_debut->bloque = ($line[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE])? $line[DRMDateView::VALUE_DETAIL_STOCKDEB_BLOQUE] * 1 : 0;
      $detail->stocks_debut->warrante = ($line[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE])? $line[DRMDateView::VALUE_DETAIL_STOCKDEB_WARRANTE] * 1 : 0;
      $detail->stocks_debut->instance = ($line[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE])? $line[DRMDateView::VALUE_DETAIL_STOCKDEB_INSTANCE] * 1 : 0;
      $detail->stocks_debut->commercialisable = ($line[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE])? $line[DRMDateView::VALUE_DETAIL_STOCKDEB_COMMERCIALISABLE] * 1 : 0;
      //$detail->total_entrees = ($line[DRMDateView::VALUE_DETAIL_ENTREES])? $line[DRMDateView::VALUE_DETAIL_ENTREES] : 0;
      $detail->entrees->achat = ($line[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT])? $line[DRMDateView::VALUE_DETAIL_ENTREES_ACHAT] * 1 : 0;
      $detail->entrees->recolte = ($line[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE])? $line[DRMDateView::VALUE_DETAIL_ENTREES_RECOLTE] * 1 : 0;
      $detail->entrees->repli = ($line[DRMDateView::VALUE_DETAIL_ENTREES_REPLI])? $line[DRMDateView::VALUE_DETAIL_ENTREES_REPLI] * 1 : 0;
      $detail->entrees->declassement = ($line[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT])? $line[DRMDateView::VALUE_DETAIL_ENTREES_DECLASSEMENT] * 1 : 0;
      $detail->entrees->mouvement = ($line[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT])? $line[DRMDateView::VALUE_DETAIL_ENTREES_MOUVEMENT] * 1 : 0;
      $detail->entrees->crd = ($line[DRMDateView::VALUE_DETAIL_ENTREES_CRD])? $line[DRMDateView::VALUE_DETAIL_ENTREES_CRD] * 1 : 0;
      //$detail->total_sorties = ($line[DRMDateView::VALUE_DETAIL_SORTIES])? $line[DRMDateView::VALUE_DETAIL_SORTIES] * 1 : 0;
      $detail->sorties->vrac = ($line[DRMDateView::VALUE_DETAIL_SORTIES_VRAC])? $line[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] * 1 : 0;
      $detail->sorties->export = ($line[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT])? $line[DRMDateView::VALUE_DETAIL_SORTIES_EXPORT] * 1 : 0;
      $detail->sorties->factures = ($line[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES])? $line[DRMDateView::VALUE_DETAIL_SORTIES_FACTURES] * 1 : 0;
      $detail->sorties->crd = ($line[DRMDateView::VALUE_DETAIL_SORTIES_CRD])? $line[DRMDateView::VALUE_DETAIL_SORTIES_CRD] * 1 : 0;
      $detail->sorties->consommation = ($line[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION])? $line[DRMDateView::VALUE_DETAIL_SORTIES_CONSOMMATION] * 1 : 0;
      $detail->sorties->pertes = ($line[DRMDateView::VALUE_DETAIL_SORTIES_PERTES])? $line[DRMDateView::VALUE_DETAIL_SORTIES_PERTES] * 1 : 0;
      $detail->sorties->declassement = ($line[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT])? $line[DRMDateView::VALUE_DETAIL_SORTIES_DECLASSEMENT] * 1 : 0;
      $detail->sorties->repli = ($line[DRMDateView::VALUE_DETAIL_SORTIES_REPLI])? $line[DRMDateView::VALUE_DETAIL_SORTIES_REPLI] * 1 : 0;
      $detail->sorties->mouvement = ($line[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT])? $line[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT] * 1 : 0;
      $detail->sorties->distillation = ($line[DRMDateView::VALUE_DETAIL_SORTIES_MOUVEMENT])? $line[DRMDateView::VALUE_DETAIL_SORTIES_DISTILLATION] * 1 : 0;
      $detail->sorties->lies = ($line[DRMDateView::VALUE_DETAIL_SORTIES_LIES])? $line[DRMDateView::VALUE_DETAIL_SORTIES_LIES] * 1 : 0;
      //$detail->total = ($line[DRMDateView::VALUE_DETAIL_TOTAL])? $line[DRMDateView::VALUE_DETAIL_TOTAL] : 0;
      $detail->stocks_fin->bloque = ($line[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE])? $line[DRMDateView::VALUE_DETAIL_STOCKFIN_BLOQUE] * 1 : 0;
      $detail->stocks_fin->warrante = ($line[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE])? $line[DRMDateView::VALUE_DETAIL_STOCKFIN_WARRANTE] * 1 : 0;
      $detail->stocks_fin->instance = ($line[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE])? $line[DRMDateView::VALUE_DETAIL_STOCKFIN_INSTANCE] * 1 : 0;
      $detail->stocks_fin->commercialisable = ($line[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE])? $line[DRMDateView::VALUE_DETAIL_STOCKFIN_COMMERCIALISABLE] * 1 : 0;
  }

  public function importDRM($options = null) 
  {
    $this->config = ConfigurationClient::getCurrent();
    $this->drm = null;
    $this->errors = array();
    $drmClient = DRMClient::getInstance();
	$numLine = 0;
      foreach ($this->getCsv() as $line) {
      	$numLine++;
      	if (!($drm = $drmClient->find($drmClient->buildId($line[DRMDateView::VALUE_IDENTIFIANT_DECLARANT], $drmClient->buildPeriode($line[DRMDateView::VALUE_ANNEE], $line[DRMDateView::VALUE_MOIS]), $line[DRMDateView::VALUE_VERSION])))) {
      		$drm = $drmClient->createBlankDoc($line[DRMDateView::VALUE_IDENTIFIANT_DECLARANT], $drmClient->buildPeriode($line[DRMDateView::VALUE_ANNEE], $line[DRMDateView::VALUE_MOIS]));
      		$drm->version = ($line[DRMDateView::VALUE_VERSION])? $line[DRMDateView::VALUE_VERSION] : null;
      		$drm->mode_de_saisie = ($line[DRMDateView::VALUE_MODEDESAISIE])? $line[DRMDateView::VALUE_MODEDESAISIE] : DRMClient::MODE_DE_SAISIE_PAPIER;
      		$drm->identifiant_ivse = ($line[DRMDateView::VALUE_IDIVSE])? $line[DRMDateView::VALUE_IDIVSE] : null;
      		$drm->identifiant_drm_historique = ($line[DRMDateView::VALUE_IDDRM])? $line[DRMDateView::VALUE_IDDRM] : null;
      		$drm->precedente = ($line[DRMDateView::VALUE_ANNEE_PRECEDENTE] && $line[DRMDateView::VALUE_MOIS_PRECEDENTE])? $drmClient->buildId($line[DRMDateView::VALUE_IDENTIFIANT_DECLARANT], $drmClient->buildPeriode($line[DRMDateView::VALUE_ANNEE_PRECEDENTE], $line[DRMDateView::VALUE_MOIS_PRECEDENTE]), $line[DRMDateView::VALUE_VERSION_PRECEDENTE]) : null;
      	}
      	$detail = $drm->addProduit($this->getHashProduit($line), explode('|', $line[DRMDateView::VALUE_LABELS]));
      	switch($line[DRMDateView::VALUE_TYPE]) {
			case 'DETAIL':
	  			$this->parseDetail($detail, $line);
	  			break;
			case 'CONTRAT':
	  			$this->parseContrat($detail, $line);
	  			break;
		}
		$drm->validate();
	  	$drm->valide->date_signee = self::datize($line[DRMDateView::VALUE_DATEDESIGNATURE]);
	  	$drm->valide->date_saisie = self::datize($line[DRMDateView::VALUE_DATEDESAISIE]);
	  	
      	$validator = new DRMValidation($drm, $options);
    	if ($validator->hasErrors()) {
      		foreach($validator->getErrors() as $err) {
				$this->errors[] = array('line' => $numLine, 'message' => $err->getMessage());
      		}
      		throw new sfException('CSV has errors');
    	} else {
    		$drm->save();
    	}
      }
  }

  public function getErrors() 
  {
    return $this->errors;
  }
}
