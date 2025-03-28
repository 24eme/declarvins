<?php

/**
 * import actions.
 *
 * @package    declarvin
 * @subpackage import
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ediActions extends sfActions
{

    public static $EXCEPTION_PRESENCE_PRODUITS_EDIDRM = [
        'declaration/certifications/AOP/genres/TRANQ/appellations/BAN'
    ];

  protected function getCompte() {
  	return acCouchdbManager::getClient('_Compte')->retrieveByLogin($_SERVER['PHP_AUTH_USER']);
  }

  protected function securizeInterpro($interpro)
  {
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $interpro = InterproClient::getInstance()->find($interpro);
    if (!$interpro) {
    	throw new error401Exception("Accès restreint");
    }
  	$interpros = array_keys($this->getCompte()->interpro->toArray());
  	if (!in_array($interpro->_id, $interpros)) {
  		throw new error401Exception("Accès restreint");
  	}
  	return $interpro;
  }

  protected function securizeEtablissement($etablissement)
  {
    if (!preg_match('/^ETABLISSEMENT-/', $etablissement)) {
		$etablissement = 'ETABLISSEMENT-'.$etablissement;
    }
    if ($this->getCompte()->exist('tiers')) {
  		$etablissements = array_keys($this->getCompte()->tiers->toArray());
	  	if (!in_array($etablissement, $etablissements)) {
	  		throw new error401Exception("Accès restreint");
	  	}
    } else {
    	if ($etab = EtablissementClient::getInstance()->find($etablissement)) {
    		$this->securizeInterpro($etab->interpro);
    	} else {
    		throw new error401Exception("Accès restreint");
    	}
    }
  }

  protected function securizeOioc($oioc)
  {
    if (!preg_match('/^OIOC-/', $oioc)) {
		$oioc = 'OIOC-'.$oioc;
    }
    if ($this->getCompte()->exist('oioc')) {
  		if ($oioc != $this->getCompte()->oioc) {
	  		throw new error401Exception("Accès restreint");
	  	}
    } else {
    	throw new error401Exception("Accès restreint");
    }
  }

  public function executeStreamVrac(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $output = $request->getParameter('output');
    $this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $vracs = $this->vracCallback($interpro, VracDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows);
	if ($output == 'txt') {
		return $this->renderTxt($vracs, "VRAC", $interpro, array(VracDateView::VALUE_ACHETEUR_ID, VracDateView::VALUE_VENDEUR_ID, VracDateView::VALUE_MANDATAIRE_ID));
	}
	return $this->renderCsv($vracs, VracDateView::VALUE_DATE_SAISIE, "VRAC", $dateTime->format('c'), $interpro, array(VracDateView::VALUE_ACHETEUR_ID, VracDateView::VALUE_VENDEUR_ID, VracDateView::VALUE_MANDATAIRE_ID));
  }

  public function executeStreamTransaction(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $oioc = $request->getParameter('oioc');
    $this->securizeOioc($oioc);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    $interpro = current(array_keys($this->getCompte()->interpro->toArray()));
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $vracs = $this->transactionCallback($interpro, VracOiocView::getInstance()->findByOiocAndDate($oioc, OIOC::STATUT_EDI, "vrac", $dateForView->modify('-1 second')->format('c'))->rows);
    return $this->renderCsv($vracs, VracOiocView::VALUE_DATE_SAISIE, "TRANSACTION", $dateTime->format('c'), $interpro, array(VracDateView::VALUE_ACHETEUR_ID, VracDateView::VALUE_VENDEUR_ID));
  }

  public function executeStreamDAE(sfWebRequest $request)
  {
  	ini_set('memory_limit', '4096M');
  	set_time_limit(0);
  	$date = $request->getParameter('datedebut');
  	$interpro = $request->getParameter('interpro');
  	//$this->securizeInterpro($interpro);
  	if (!$date) {
  		return $this->renderText("Pas de date définie");
  	}
  	if (!preg_match('/^INTERPRO-/', $interpro)) {
  		$interpro = 'INTERPRO-'.$interpro;
  	}
  	$dateTime = new DateTime($date);
  	$dateForView = new DateTime($date);
  	$entetes = array("#date de la commercialisation","identifiant declarvins du déclarant","numéro d'accises du déclarant","nom du déclarant","stat famille","stat sous famille","stat département","code ou nom de la certification du vin","nom ou code du genre du vin","nom ou code du appellation du vin","nom ou code du mention du vin","nom ou code du lieu du vin","nom ou code du couleur du vin","nom ou code du cépage du vin","Le complément du vin","Le libellé personnalisé du vin","label du produit","mention de domaine ou château revendiqué","millésime","primeur","n° accise de l'acheteur","nom acheteur","type acheteur","nom du pays de destination","type de conditionnement","libellé conditionnement","contenance conditionnement en litres","quantité de conditionnement","prix unitaire","stat qtt hl","stat prix hl");
  	$daes = $this->daeCallback($interpro, EdiDAEView::getInstance()->findByDate($dateForView->modify('-1 second')->format('c'))->rows);
  	return $this->renderCsv($daes, EdiDAEView::VALUE_DATE, "DAE", $dateTime->format('c'), $interpro, array(), $entetes);
  }

  public function executeStreamDRM(sfWebRequest $request)
  {
  	ini_set('memory_limit', '4096M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $famille = $request->getParameter('famille');
    $output = $request->getParameter('output');
  	$this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $entetes = array(
"#type ligne",
"identifiant declarvins du déclarant",
"raison sociale du déclarant",
"année période drm",
"mois période drm",
"version drm",
"champ vide",
"champ vide",
"champ vide",
"nom de la certification du vin",
"code de la certification du vin",
"nom du genre du vin",
"code du genre du vin",
"nom de l'appellation du vin",
"code de l'appellation du vin",
"nom du lieu du vin",
"code du lieu du vin",
"nom de la couleur du vin",
"code de la couleur du vin",
"nom du cépage du vin",
"code du cépage du vin",
"millésime du vin",
"champ vide",
"nom du label",
"code du label",
"label complementaire",
"total debut mois",
"stocks debut bloque",
"stocks debut warrante",
"stocks debut instance",
"stocks debut commercialisable",
"total entrees",
"entrees achat",
"entrees recolte",
"entrees repli",
"entrees declassement",
"entrees mouvement",
"entrees crd",
"total sorties",
"sorties vrac",
"sorties export",
"sorties factures",
"sorties crd",
"sorties consommation",
"sorties pertes",
"sorties declassement",
"sorties repli",
"sorties mouvement",
"sorties distillation",
"sorties lies",
"total",
"stocks fin bloque",
"stocks fin warrante",
"stocks fin instance",
"stocks fin commercialisable",
"date saisie drm",
"date signature drm",
"mode saisie drm",
"code cvo",
"taux cvo",
"volume cvo",
"montant cvo",
"campagne drm",
"identifiant drm historique",
"identifiant drm ivse",
"numéro visa contrat si type CONTRAT",
"volume sortie contrat si type CONTRAT",
"commentaire drm",
"drm referente",
"drm a contrats manquants",
"drm a igp manquants",
"observation mouvement",
"entrees vci",
"famille du declarant",
"sous famille du declarant",
"code inao du vin",
"libelle fiscal du vin",
"tav",
"entrees manipulation",
"entrees embouteillage",
"entrees travail",
"entrees distillation",
"entrees excedent",
"sorties crd_acquittes",
"sorties mutages",
"sorties vci",
"sorties autres_interne",
"sorties embouteillage",
"sorties travail",
"sorties distillation",
"total_debut_mois acquitté",
"entrees achat acquitté",
"entrees autres acquitté",
"sorties crd acquitté",
"sorties replacement acquitté",
"sorties autres acquitté",
"total acquitté");
    $drms = $this->drmCallback($interpro, DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows, $famille);
	if ($output == 'txt') {
		return $this->renderTxt($drms, "DRM", $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
	}
    return $this->renderCsv($drms, DRMDateView::VALUE_DATEDESAISIE, "DRM", $dateTime->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));//, $entetes);
  }

  public function executeStreamDRMInterpro(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);

  	$interpro = $this->securizeInterpro($request->getParameter('interpro'));
    $date = str_replace(array('h', 'H', 'm', 'M'), ':', $request->getParameter('datedebut'));
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    $dateForView = new DateTime($date);
    $items = EdiDrmpartenaireView::getInstance()->findByInterproDate($interpro->_id, $dateForView->format('c'))->rows;
    $csv = '';
    $lastDate = $dateForView->format('c');
    foreach ($items as $item) {
    	$csv .= implode(';', $item->value);
    	$csv .= "\n";
    	if ($lastDate < $item->key[EdiDrmpartenaireView::KEY_DATE]) {
    		$lastDate = $item->key[EdiDrmpartenaireView::KEY_DATE];
    	}
    }

    $lastDate = new DateTime($lastDate);
    $filename = 'DRM_'.strtolower($interpro->identifiant).'_'.$dateForView->format('Y-m-d\TH\hi\ms').'_'.$lastDate->format('Y-m-d\TH\hi\ms').'.csv';
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$filename);
    return $this->renderText($csv);
  }

  public function executeStreamVracInterpro(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);

  	$interpro = $this->securizeInterpro($request->getParameter('interpro'));
  	$items = EdiVracpartenaireView::getInstance()->findByZoneStatut($interpro->zone, VracClient::STATUS_CONTRAT_NONSOLDE)->rows;

  	$csv = '';
	foreach ($items as $item) {
		$csv .= implode(';', $item->value);
		$csv .= "\n";
	}

  	$filename = 'contrats_achat_non_soldes_'.strtolower($interpro->identifiant).'.csv';
  	$this->response->setContentType('text/csv');
  	$this->response->setHttpHeader('md5', md5($csv));
  	$this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$filename);
  	return $this->renderText($csv);
  }

  public function executeStreamCampagneDRM(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {
    	return $this->renderText("Campagne non valide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $campagneManager = new CampagneManager('08-01');
    $datedebut = new DateTime($campagneManager->getDateDebutByCampagne($campagne));
    $datefin = new DateTime($campagneManager->getDateFinByCampagne($campagne));
    $dateForViewDebut = new DateTime($campagneManager->getDateDebutByCampagne($campagne));
    $dateForViewfin = new DateTime($campagneManager->getDateFinByCampagne($campagne));
    $drms = DRMDateView::getInstance()->findByInterproAndDates($interpro, array('begin' => $dateForViewDebut->modify('-1 second')->format('c'), 'end' => $dateForViewfin->modify('+1 day')->modify('-1 second')->format('c')), true);
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
  }

  public function executeStreamAnneeDRM(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $annee = $request->getParameter('annee');
    $interpro = $request->getParameter('interpro');
  	$this->securizeInterpro($interpro);
    if (!preg_match('/^([0-9]{4})$/', $annee, $annees)) {
    	return $this->renderText("Année non valide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $datedebut = new DateTime($annee.'-01-01');
    $datefin = new DateTime($annee.'-01-01');
    $datefin->modify("+1 year")->modify("-1 day");
    $dateForViewDebut = new DateTime($annee.'-01-01');
    $dateForViewfin = new DateTime($annee.'-01-01');
    $dateForViewfin->modify("+1 year")->modify("-1 day");
    $drms = DRMDateView::getInstance()->findByInterproAndDates($interpro, array('begin' => $dateForViewDebut->modify('-1 second')->format('c'), 'end' => $dateForViewfin->modify('+1 second')->format('c')), true);
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
  }

  public function executeStreamDRMEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut', null);
    $dateForView = null;
    if ($date) {
    	$dateTime = new DateTime($date);
    	$date = $dateTime->format('c');
    	$dateForView = new DateTime($date);
    	;
    }
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etab = EtablissementClient::getInstance()->find($etablissement);
    $drms = DRMEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView->modify('-1 second')->format('c'));
    return $this->renderCsv($drms->rows, DRMEtablissementView::VALUE_DATEDESAISIE, "DRM", $date, $etab->interpro);
  }

  public function executeEdiV2(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
  	$csv_file = null;
  	$format = $request->getParameter('format', 'csv');
    if ($this->drm = DRMClient::getInstance()->find($request->getParameter('id_drm', null))) {
        $export = new DRMExportCsvEdi($this->drm);
    	$csv_file = $export->exportEDI($format);
    }
    if (!$csv_file) {
    	$this->response->setStatusCode(204);
    	return $this->renderText(null);
    }

    switch ($format) {
    	case 'csv':
    		return $this->renderTextCsv($csv_file); break;
    	case 'xml':
    		return $this->renderTextXml($csv_file); break;
    	case 'debug':
            return $this->compareXSD($csv_file); break;
    	default:
    		return $this->renderText($csv_file); break;
    }
  }

  public function compareXSD($csv_file)
  {
      $xsd = $this->drm->isNegoce()
             ? sfConfig::get('sf_data_dir').'/ciel/ciel-dti-plus_v1.0.23.xsd'
             : sfConfig::get('sf_data_dir').'/ciel/echanges-interprofession-1.17.xsd';

      if (! is_file($xsd)) {
          throw new sfException("Fichier xsd non trouvé");
      }

      $xml = new DOMDocument();
      $xml->loadXML($csv_file);
      libxml_use_internal_errors(true);
      $valid = $xml->schemaValidate($xsd);

      $errors = ['Validation du XML...'];
      $errors[] = 'Schéma utilisé : '.basename($xsd);
      if (! $valid) {
        foreach (libxml_get_errors() as $error) {
            switch ($error->level) {
              case 1: $level = "WARN"; break;
              case 2: $level = "ERR"; break;
              case 3: $level = "FATAL"; break;
            }
            $errors[] = "[$level][$error->code] $error->message";
        }
      } else {
          $errors[] = 'Fichier conforme.';
      }
      $errors[] = 'Fini';

      libxml_use_internal_errors(false);

      return $this->renderText('<pre>'.implode(PHP_EOL, $errors));
  }

  public function renderTextCsv($csv_file) {
  	$this->response->setHttpHeader('md5', md5($csv_file));
  	$this->response->setHttpHeader('LastDocDate', date('r'));
  	$this->response->setHttpHeader('Last-Modified', date('r'));
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=ediv2.csv");
    return $this->renderText($csv_file);
  }

  public function renderTextXml($csv_file) {
  	$this->response->setHttpHeader('md5', md5($csv_file));
  	$this->response->setHttpHeader('LastDocDate', date('r'));
  	$this->response->setHttpHeader('Last-Modified', date('r'));
  	$this->response->setContentType('text/xml');
  	$this->response->setHttpHeader('Content-Disposition', "attachment; filename=ediv2.xml");
  	return $this->renderText($csv_file);
  }


  public function executePushTransaction(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
  	$oioc = $request->getParameter('oioc');
    $this->securizeOioc($oioc);
  	$oioc = OIOCClient::getInstance()->find($oioc);
  	$formUploadCsv = new UploadCSVForm();
  	$result = array();
  	$lignes = array();
  	if ($request->isMethod('post')) {
  		$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
  		if ($formUploadCsv->isValid()) {
  			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
  			$import = new TransactionUpdate($csv->getCsv(), $oioc);
			if ($import->hasErrors()) {
				return $this->renderSimpleCsv($import->getLogs(), "transaction");
			}

			$contrats = $import->getContrats();
			$traites = array();
			foreach ($contrats as $contrat) {
				if (!in_array($contrat->_id, $traites)) {
					$traites[] = $contrat->_id;
					$contrat->save(false);
					$result[] = array('SUCCESS', 'CONTRAT', null, 'Le contrat '.$contrat->_id.' a été mis à jour avec succès');
				}
			}

  		} else {
  			$result[] = array('ERREUR', 'FORMAT', null, 'Fichier csv non valide');
  		}
  	} else {
  		$result[] = array('ERREUR', 'ACCES ', null, 'Seules les requêtes de type POST sont acceptées');
  	}
  	return $this->renderSimpleCsv($result, "transaction");
  }

  public function executePushDRMEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etab = EtablissementClient::getInstance()->find($etablissement);
	$formUploadCsv = new UploadCSVForm();
    $result = array();
	if ($request->isMethod('post')) {
    	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
      	if ($formUploadCsv->isValid()) {
			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
			$import = new DRMImport($csv->getCsv(), $etab);
			if ($import->hasErrors()) {
				return $this->renderSimpleCsv($import->getLogs(), "drm");
			}
		    $drm = $import->getDrm();
		    if ($import->hasErrors()) {
				return $this->renderSimpleCsv($import->getLogs(), "drm");
		    }
		    $drm->validate();
		    $drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_EDI;
		    $drm->save();
		    $result[] = array('SUCCESS', 'DRM', null, 'La DRM '.$drm->_id.' a été importée avec succès');
      	} else {
      		$result[] = array('ERREUR', 'FORMAT', null, 'Fichier csv non valide');
      	}
    } else {
    	$result[] = array('ERREUR', 'ACCES ', null, 'Seules les requêtes de type POST sont acceptées');
    }
    return $this->renderSimpleCsv($result, "drm");
  }

  public function executeGetBilanDrmEtablissement(sfWebRequest $request)
  {
  	$etablissement = $request->getParameter('etablissement');
  	$this->securizeEtablissement($etablissement);
  	$result = array(array('Période', 'Code statut', 'Libellé statut'));
    $historique = new DRMHistorique($etablissement);
    $drms = $historique->getDRMs();
    $libelles = DRMClient::getAllLibellesStatusBilan();
    $item = 0;
    foreach ($drms as $d) {
        if ($item >= 23) {
            break;
        }
        if (isset($result[$d->periode])) {
            continue;
        }
        $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($d->identifiant, $d->periode);
        $statut = $drm->getStatutBilan();
        if (in_array($statut, array(DRMClient::DRM_STATUS_BILAN_IGP_MANQUANT, DRMClient::DRM_STATUS_BILAN_CONTRAT_MANQUANT, DRMClient::DRM_STATUS_BILAN_IGP_ET_CONTRAT_MANQUANT))) {
            $statut = DRMClient::DRM_STATUS_BILAN_VALIDE;
        }
        $result[] = array(str_replace('-', '', $drm->periode), $statut, $libelles[$statut]);
        $item++;
    }
  	return $this->renderSimpleCsv($result, "bilan");
  }

  public function executePushDRMEtablissementV2(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
  	$etablissement = $request->getParameter('etablissement');
  	$this->securizeEtablissement($etablissement);
  	$etab = EtablissementClient::getInstance()->find($etablissement);
  	$historique = new DRMHistorique($etab->identifiant);
  	$formUploadCsv = new UploadCSVForm();
  	$result = array();
  	if ($request->isMethod('post')) {
      	if (!$historique->hasDRMInProcess()) {
  		$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
  		if ($formUploadCsv->isValid()) {
  			try {
  				$drm = DRMClient::getInstance()->createDoc($etab->identifiant);
  				$file = sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5();
  				$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_EDI;
  				$drmCsvEdi = new DRMImportCsvEdi($file, $drm);
  				$drmCsvEdi->checkCSV();

  				if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
  					foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
  						if ($erreur->num_ligne > 0) {
  							$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
  						} else {
  							$result[] = array('ERREUR', 'CSV', null, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
  						}
  					}
  				} else {
	  				$drmCsvEdi->importCsv();
	  				$drm->constructId();
	  				$errors = 0;
	  				if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
	  					foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
	  						$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
	  						$errors++;
	  					}
	  				}
	  				if ($drm->identifiant != $etablissement) {
	  					$result[] = array('ERREUR', 'ACCES', null, 'error_access_nonpermis', "Import restreint à l'établissement ".$etablissement);
	  					$errors++;
	  				}
	  				if (!$etab->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
	  					$result[] = array('ERREUR', 'ACCES', null, 'error_access_droits', "L'établissement ".$etablissement." n'est pas autorisé à déclarer des DRMs");
	  					$errors++;
	  				}
	  				if (DRMClient::getInstance()->find($drm->_id)) {
	  					$master = $drm->findMaster();
	  					if ($master->mode_de_saisie == DRMClient::MODE_DE_SAISIE_EDI) {
	  						$master = $master->generateRectificative();
	  						$drm->version = $master->version;
	  						$drm->precedente = $master->_id;
	  						$drm->constructId();
	  					} else {
	  						$result[] = array('ERREUR', 'ACCES', null, 'error_access_drmexist', "La DRM ".$drm->periode." pour ".$drm->identifiant." est déjà existante dans la base DeclarVins");
	  						$errors++;
	  					}
	  				}
	  				if (!$errors) {
		  				$drm->update();
		  				$validation = new DRMValidation($drm);

		  				if (!$validation->isValide()) {
		  					foreach ($validation->getErrors() as $error) {
		  						$result[] = array('ERREUR', 'CSV', null, $error->getIdentifiant(), str_replace('Erreur, ', '', $error));
		  					}
		  				} else {
		  					$route = 'drm_';
					    	$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_EDI;
					    	if ($etab->isTransmissionCiel()) {
					    		$drm->etape = 'validation';
					    		$route .= 'validation';
					    	} else {
					    		$drm->validate();
					    		$route .= 'visualisation';
					    	}
					    	$drm->save();
					    	$result[] = array('SUCCES', 'CSV', null, sfConfig::get('app_base_url').str_replace(array('edi.php/', 'edi_dev.php/'), '', $this->generateUrl($route, $drm)), 'La DRM '.$drm->periode." pour ".$drm->identifiant.' a été importée avec succès');
		  				}
	  				}
  				}

  			} catch(Exception $e) {
  				$result[] = array('ERREUR', 'CSV', null, 'error_500', $e->getMessage());
  			}
  		} else {
      		$result[] = array('ERREUR', 'ACCES', null, 'error_notvalid_inputcsv', 'Fichier csv non valide');
      	}
        } else {
        	$result[] = array('ERREUR', 'ACCES ', null, 'error_access_drmencours', 'Une DRM est en cours de saisie');
        }

  	} else {
  		$result[] = array('ERREUR', 'ACCES ', null, 'error_access_rest', 'Seules les requêtes de type POST sont acceptées');
  	}
  	return $this->renderSimpleCsv($result, "drm");
  }

  public function executeStreamVracEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut', null);
    $dateForView = null;
    if ($date) {
    	$dateTime = new DateTime($date);
    	$date = $dateTime->format('c');
    	$dateForView = new DateTime($date);
    	$dateForView->modify('-1 second')->format('c');
    }
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etablissementObject = EtablissementClient::getInstance()->find($etablissement);
    $vracs = $this->vracCallback($etablissementObject->interpro, VracEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView)->rows);
    return $this->renderCsv($vracs, VracEtablissementView::VALUE_DATE_SAISIE, "VRAC", $date, $etablissementObject->interpro);
  }

  public function executePushVracEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etablissementObject = EtablissementClient::getInstance()->find($etablissement);
    $interpro = ($etablissementObject)? $etablissementObject->interpo : null;
	$formUploadCsv = new UploadCSVForm();
    $result = array();
	if ($request->isMethod('post')) {
    	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
      	if ($formUploadCsv->isValid()) {
			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
	      	$lignes = $csv->getCsv();
    		$vracClient = VracClient::getInstance();
    		$vracConfiguration = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
		    $numLigne = 0;
		  	foreach ($lignes as $ligne) {
		    	$numLigne++;
		    	$import = new VracDetailImport($ligne, $vracClient, $vracConfiguration);
		    	$vrac = $import->getVrac();
		    	if ($import->hasErrors()) {
		    		$result[$numLigne] = array('ERREUR', 'LIGNE', $numLigne, implode(' - ', $import->getLogs()));
		    	} else {
		    		$result[$numLigne] = array('OK', '', $numLigne, '');
		    		$vrac->save();
		    	}
		    }
      	} else {
      		$result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
      	}

    } else {
    	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    }
    return $this->renderSimpleCsv($result, "vrac");
  }

  public function executeStreamStatistiquesBilanDrm(sfWebRequest $request)
  {
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!preg_match("/^[0-9]{4}-[0-9]{4}$/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
	$interpro = str_replace('INTERPRO-', '', $interpro);
    $targetFile = sfConfig::get('sf_web_dir').'/exports/BILANDRM/'.$interpro.'/'.$campagne.'/bilan.csv';
    if (!file_exists($targetFile)) {
        throw new Exception('Aucun bilan pour la configuration : interpro : '.$interpro.' campagne : '.$campagne.' !');
    }
    $csv_file = file_get_contents($targetFile);

    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$campagne.".csv");
    return $this->renderText($csv_file);
  }

  public function executeStreamStatistiquesBilanDrmManquantes(sfWebRequest $request)
  {
    $campagne = $request->getParameter('campagne');
    $periode = $request->getParameter('periode');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{4}/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
    if (!$periode) {
        return $this->renderText("Pas de periode définie");
    }
	$periode = str_replace('-', '', $periode);
    if (!preg_match("/^[0-9]{6}$/", $periode)) {
    	return $this->renderText("Periode invalide");
    }
    $interpro = str_replace('INTERPRO-', '', $interpro);
    $targetFile = sfConfig::get('sf_web_dir').'/exports/BILANDRM/'.$interpro.'/'.$campagne.'/'.$periode.'_bilan.csv';
    if (!file_exists($targetFile)) {
        throw new Exception('Aucun bilan N-1 pour la configuration : interpro : '.$interpro.' campagne : '.$campagne.' periode : '.$periode.' !');
    }
    $csv_file = file_get_contents($targetFile);

    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$campagne."_".$periode.".csv");
    return $this->renderText($csv_file);
  }

  public function executeViewDRM(sfWebRequest $request)
  {
    $this->securizeEtablissement($request->getParameter('identifiant'));
    $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriodeAndRectificative(
    		$request->getParameter('identifiant'),
        	DRMClient::getInstance()->buildPeriode($request->getParameter('annee'), $request->getParameter('mois')),
        	$request->getParameter('rectificative')
    );
    $this->forward404Unless($drm);
    $csv_file = '';
	$csv = DRMCsvFile::createFromDRM($drm);
  	foreach($csv->getCsv() as $line) {
  		$csv_file .= implode(';', $line)."\n";
	}
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$drm->_id.".csv");
    return $this->renderText($csv_file);
  }

	public function executePushGrc(sfWebRequest $request)
	{
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
    	$interpro = $request->getParameter('interpro');
    	$this->securizeInterpro($interpro);
    	if (!preg_match('/^INTERPRO-/', $interpro)) {
        	$interpro = 'INTERPRO-'.$interpro;
    	}
        $interpro = InterproClient::getInstance()->getById($interpro);
        $formUploadCsv = new UploadCSVForm();
    	$result = array();
        if ($request->isMethod('post')) {
	        $formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
	        if ($formUploadCsv->isValid()) {
	        	$file = sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5();
	            $interpro->storeAttachment($file, 'text/csv', 'etablissements.csv');
	            $import = new ImportEtablissementsCsv($interpro);
	            try {
	            	$nb = $import->updateOrCreate();
	                $result[0] = array('OK', '', 0, $nb.' établissement(s) importé(s)');
			      	if (count($import->getErrors()) > 0) {
			      		throw new sfException("has errors");
			      	}
	            } catch (Exception $e) {
	            	foreach ($import->getErrors() as $k => $v) {
	                	$result[$k] = array('ERREUR', 'LIGNE', $k+1, implode(' - ', $v));
	                }
	            }
	        } else {
	                $result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
	        }
    	} else {
        	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    	}
    	return $this->renderSimpleCsv($result, "grc");
	}

	public function executeStreamGrc(sfWebRequest $request)
	{
		ini_set('memory_limit', '2048M');
	  	set_time_limit(0);
	    $interproId = $request->getParameter('interpro');
		$output = $request->getParameter('output');
	    $this->securizeInterpro($interproId);
	    if (!preg_match('/^INTERPRO-/', $interproId)) {
			$interproId = 'INTERPRO-'.$interproId;
	    }
	    $interpro = InterproClient::getInstance()->find($interproId);
		$rows = EtablissementAllView::getInstance()->findAllByZone($interpro->zone);
		$eClient = EtablissementClient::getInstance();
		$zClient = ConfigurationZoneClient::getInstance();
		$result = "#ID INTERPRO;ID HISTORIQUE;NUM CONVENTION;INTERPRO REF;SIRET;CNI;CVI;NUM ACCISES;NUM TVA INTRACOM;EMAIL;TEL;FAX;RAISON SOCIALE;NOM COM;ADRESSE;COMMUNE;CP;PAYS;FAMILLE;SOUS FAMILLE;ADRESSE COMPTA;COMMUNE COMPTA;CP COMPTA;PAYS COMPTA;DOUANE;;STATUT;NOM;PRENOM;FONCTION;EMAIL;TEL;FAX;NUM CARTE PRO;ZONES;ZONES;CORRESPONDANCES INTERPRO;CONVENTIONNE CIEL\n";
		foreach($rows as $row) {
			$etablissement = $eClient->find($row->id);
			$compte = $etablissement->getCompteObject();
			if ($etablissement->isTransmissionCiel()) {
				$convention = 'oui';
			} else {
				$convention = 'non';
				if ($compte) {
				if ($c = $compte->getConventionCiel()) {
					if ($c->valide) {
						$convention = 'att';
					}
				}
				}
			}
			$result .= $etablissement->identifiant;
			$result .= ';';
			$result .= $etablissement->num_interne;
			$result .= ';';
			$result .= str_replace('CONTRAT-', '', $etablissement->contrat_mandat);
			$result .= ';';
			$result .= str_replace('INTERPRO-', '', $etablissement->interpro);
			$result .= ';';
			$result .= $etablissement->siret;
			$result .= ';';
			$result .= $etablissement->cni;
			$result .= ';';
			$result .= $etablissement->cvi;
			$result .= ';';
			$result .= $etablissement->no_accises;
			$result .= ';';
			$result .= $etablissement->no_tva_intracommunautaire;
			$result .= ';';
			$result .= $etablissement->email;
			$result .= ';';
			$result .= $etablissement->telephone;
			$result .= ';';
			$result .= $etablissement->fax;
			$result .= ';';
			$result .= $etablissement->raison_sociale;
			$result .= ';';
			$result .= $etablissement->nom;
			$result .= ';';
			$result .= $etablissement->siege->adresse;
			$result .= ';';
			$result .= $etablissement->siege->commune;
			$result .= ';';
			$result .= $etablissement->siege->code_postal;
			$result .= ';';
			$result .= $etablissement->siege->pays;
			$result .= ';';
			$result .= $etablissement->famille;
			$result .= ';';
			$result .= $etablissement->sous_famille;
			$result .= ';';
			$result .= $etablissement->comptabilite->adresse;
			$result .= ';';
			$result .= $etablissement->comptabilite->commune;
			$result .= ';';
			$result .= $etablissement->comptabilite->code_postal;
			$result .= ';';
			$result .= $etablissement->comptabilite->pays;
			$result .= ';';
			$result .= $etablissement->service_douane;
			$result .= ';';
			$result .= '';
			$result .= ';';
			$result .= $etablissement->statut;
			$result .= ';';
			$result .= ($compte)? $compte->nom : '';
			$result .= ';';
			$result .= ($compte)? $compte->prenom : '';
			$result .= ';';
			$result .= ($compte)? $compte->fonction : '';
			$result .= ';';
			$result .= ($compte)? $compte->email : '';
			$result .= ';';
			$result .= ($compte)? $compte->telephone : '';
			$result .= ';';
			$result .= ($compte)? $compte->fax : '';
			$result .= ';';
			$result .= $etablissement->no_carte_professionnelle;
			$result .= ';';
			$zones = array();
			$zonesLibelles = array();
			$correspondances = array();
			if ($etablissement->exist('zones')) {
				foreach ($etablissement->zones as $zoneId => $zone) {
					if (!$zone->transparente) {
						$zones[] = $zClient->getGrcCode($zoneId);
						$zonesLibelles[] = $zClient->getGrcLibelle($zoneId);
					}
				}
			}
			if ($etablissement->exist('correspondances')) {
				foreach ($etablissement->correspondances as $inter => $id) {
						$correspondances[] = str_replace('INTERPRO-', '', $inter).':'.$id;
				}
			}
			$result .= ($zones)? implode('|', $zones) : '';
			$result .= ';';
			$result .= ($zonesLibelles)? implode('|', $zonesLibelles) : '';
			$result .= ';';
			$result .= ($correspondances)? implode('|', $correspondances) : '';
			$result .= ';';
			$result .= $convention;
			$result .= "\n";
		}
	    if (!$result) {
			$this->response->setStatusCode(204);
			return $this->renderText(null);
	    }
	    $date = date('r');
	    $now = date('c');
		if ($output == 'txt') {
			return $this->renderText($result);
		}
		$this->response->setContentType('text/csv');
	    $this->response->setHttpHeader('md5', md5($result));
	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$interproId."_GRC_".$now.".csv");
	    $this->response->setHttpHeader('LastDocDate', $date);
	    $this->response->setHttpHeader('Last-Modified', $date);
	    return $this->renderText($result);
	}


  protected function vracCallback($interpro, $items)
  {
  		$vracs = array();
  		$configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
  		foreach ($items as $item) {
  			if ($item->value[VracDateView::VALUE_MODE_SAISIE] == 'EDI') continue;
  			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
  			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
  			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
  			$item->value[VracDateView::VALUE_PRIX_UNITAIRE] = round($item->value[VracDateView::VALUE_PRIX_UNITAIRE], 2);
  			$item->value[VracDateView::VALUE_PRIX_TOTAL] = round($item->value[VracDateView::VALUE_PRIX_TOTAL], 2);
        $item->value[VracDateView::VALUE_RESPONSABLE] = str_replace('mandataire', 'courtier', $item->value[VracDateView::VALUE_RESPONSABLE]); 
  			$vracs[] = $item;
  		}
  		return $vracs;
  }


  protected function transactionCallback($interpro, $items)
  {
  		$vracs = array();
  		$configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
  		foreach ($items as $item) {
  			if ($item->value[VracOiocView::VALUE_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION) {
  				continue;
  			}
  			$item->value[VracOiocView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracOiocView::VALUE_TYPE_CONTRAT_LIBELLE]));
  			$item->value[VracOiocView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracOiocView::VALUE_CAS_PARTICULIER_LIBELLE]));
  			$item->value[VracOiocView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracOiocView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
  			$vracs[] = $item;
  		}
  		return $vracs;
  }

  protected function daeCallback($interpro, $items)
  {
  		$daes = array();
  		foreach ($items as $item) {
  			$item->value[EdiDAEView::VALUE_IDENTIFIANT_DECLARANT] = (EdiDAEView::VALUE_IDENTIFIANT_DECLARANT)? ConfigurationClient::getInstance()->anonymisation(EdiDAEView::VALUE_IDENTIFIANT_DECLARANT) : null;
  			$item->value[EdiDAEView::VALUE_ACCISES_DECLARANT] = (EdiDAEView::VALUE_ACCISES_DECLARANT)? ConfigurationClient::getInstance()->anonymisation(EdiDAEView::VALUE_ACCISES_DECLARANT) : null;
  			$item->value[EdiDAEView::VALUE_NOM_DECLARANT] = (EdiDAEView::VALUE_NOM_DECLARANT)? ConfigurationClient::getInstance()->anonymisation(EdiDAEView::VALUE_NOM_DECLARANT) : null;
  			$item->value[EdiDAEView::VALUE_ACCISES_ACHETEUR] = (EdiDAEView::VALUE_ACCISES_ACHETEUR)? ConfigurationClient::getInstance()->anonymisation(EdiDAEView::VALUE_ACCISES_ACHETEUR) : null;
  			$item->value[EdiDAEView::VALUE_NOM_ACHETEUR] = (EdiDAEView::VALUE_NOM_ACHETEUR)? ConfigurationClient::getInstance()->anonymisation(EdiDAEView::VALUE_NOM_ACHETEUR) : null;
  			$daes[] = $item;
  		}
  		return $daes;
  }

  protected function drmCallback($interpro, $items, $famille)
  {
  		$drms = array();
  		$squeeze = null;
		$numberValues = DRMDateView::numberValues();
        $conf = ConfigurationClient::getCurrent();
  		foreach ($items as $item) {
            if ($item->value[DRMDateView::VALUE_TYPE] != 'DETAIL') {
                $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT] = null;
            }
            $libelle = '';
            $hash = substr($item->key[DRMDateView::KEY_DETAIL_HASH], 0, strpos($item->key[DRMDateView::KEY_DETAIL_HASH], '/details/'));
            if ($hash && ($confProduit = $conf->getConfigurationProduit($hash))) {
                $libelle = trim($confProduit->getLibelleFormat(array(), "%format_libelle%"));
                if ($item->value[DRMDateView::VALUE_LABELS_CODE]) {
                    $libelle .= ' '.str_replace('|', ', ', $item->value[DRMDateView::VALUE_LABELS_CODE]);
                }
            }
            $item->value[DRMDateView::VALUE_DETAIL_HASH_PRODUIT_GENERATED] = ($libelle)? md5($libelle) : '';
            if ($interpro == 'INTERPRO-IR' && $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT]) {
                $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] = $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC] + $item->value[DRMDateView::VALUE_DETAIL_SORTIES_VRAC_EXPORT];
            }
			foreach ($numberValues as $val) {
				if ($item->value[$val]) {
					$item->value[$val] = number_format($item->value[$val], 5, '.', '');
				}
			}
  			if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && (is_null($item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX]) || $item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX] < 0 || !$item->value[DRMDateView::VALUE_DETAIL_CVO_CODE])) {
                $isExceptionProduit = false;
                foreach (self::$EXCEPTION_PRESENCE_PRODUITS_EDIDRM as $hash) {
                    if (strpos($item->key[DRMDateView::KEY_DETAIL_HASH], $hash) !== false) {
                        $isExceptionProduit = true;
                        break;
                    }
                }
                if (!$isExceptionProduit) {
                    $squeeze = $item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH];
                }
  			}
  			if ($interpro == 'INTERPRO-CIVP' && !$famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != 'producteur') {
  				$squeeze = $item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH];
  			}
  			if ($interpro == 'INTERPRO-IVSE' && !$famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != 'producteur' && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_SOUSFAMILLE] != 'vinificateur') {
  				$squeeze = $item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH];
  			}
  			if (($interpro == 'INTERPRO-CIVP' || $interpro == 'INTERPRO-IVSE') && $famille && $item->value[DRMDateView::VALUE_DETAIL_DECLARANT_FAMILLE] != $famille) {
  			    $squeeze = $item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH];
  			}
            if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && !$item->value[DRMDateView::VALUE_DETAIL_TOTAL_DEBUT_MOIS] && !$item->value[DRMDateView::VALUE_DETAIL_ENTREES] && !$item->value[DRMDateView::VALUE_DETAIL_SORTIES]) {
                $squeeze = $item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH];
            }
  			if ($item->value[DRMDateView::VALUE_IDDRM].$item->key[DRMDateView::KEY_DETAIL_HASH] != $squeeze) {
  				$drms[] = $item;
  			}
  		}
  		return $drms;
  }

  protected function getCsvItems($items, $type, $correspondances= array(), $tableCorrespondances = array())
  {
    $csv_file = '';
    $rc1 = chr(10);
    $rc2 = chr(13);
    foreach ($items as $item) {
    	if ($tableCorrespondances && $correspondances) {
    		foreach ($correspondances as $correspondance) {
    			if ($item->value[$correspondance] && in_array($item->value[$correspondance], array_keys($tableCorrespondances))) {
    				$item->value[$correspondance] = $tableCorrespondances[$item->value[$correspondance]];
    			}
    		}
    	}
    	if ($type == 'DRM') {
    		$item->value[DRMDateView::VALUE_DETAIL_CVO_MONTANT] = round($item->value[DRMDateView::VALUE_DETAIL_CVO_MONTANT], 2);
    	}
        $csv_file .= str_replace(array($rc1, $rc2), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)));
      	$csv_file .= "\n";
	}
	return $csv_file;
  }

  protected function renderCsv($items, $dateSaisieIndice, $type, $date = null, $interpro, $correspondances = array(), $entetes = null)
  {
    $this->setLayout(false);
    $csv_file = $this->getCsvItems($items, $type, $correspondances, $this->getTableCorrespondance($interpro));
    $startDate = ($date)? $date."_" : '';
    $lastDate = $date;
    $beginDate = '9999-99-99';
    if (!$startDate) {
    	$startDate = $beginDate."_";
    }
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    if ($entetes && is_array($entetes)) {
    	$csv_file = implode(';', str_replace(';', '-', $entetes))."\n".$csv_file;
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$type."_".$startDate.$lastDate.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText($csv_file);
  }

  protected function renderTxt($items, $type, $interpro, $correspondances = array(), $entetes = null)
  {
    $this->setLayout(false);
    $csv_file = $this->getCsvItems($items, $type, $correspondances, $this->getTableCorrespondance($interpro));
    if ($entetes && is_array($entetes)) {
    	$csv_file = implode(';', str_replace(';', '-', $entetes))."\n".$csv_file;
    }
    return $this->renderText($csv_file);
  }

  protected function getTableCorrespondance($interproId)
  {
  	$interpro = InterproClient::getInstance()->find($interproId);
  	return $interpro->correspondances->toArray();
  }

  protected function renderSimpleCsv($items, $type, $date = null)
  {
    $this->setLayout(false);
    $csv_file = '';
  	foreach ($items as $item) {
      		$csv_file .= implode(';', $item)."\n";
    }
    $lastDate = ($date)? $date : date('Ymd');
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$lastDate."_resultat_import_".$type.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText($csv_file);
  }

  // A TESTER
  public function executeUploadEtablissements(sfWebRequest $request)
  {
  	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));
    $this->securizeInterpro($this->interpro);
  	$this->formUploadCsv = new UploadCSVForm();
  	if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
  		$this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
  		if ($this->formUploadCsv->isValid()) {
  			$file = $this->formUploadCsv->getValue('file');
  			$this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
  			unlink($file->getSavedName());
  			$import = new ImportEtablissementsCsv($this->interpro);
  			$import->updateOrCreate();
  		}
  	}
  	$this->setLayout(false);
  	$this->response->setContentType('text/plain');
  }

}
