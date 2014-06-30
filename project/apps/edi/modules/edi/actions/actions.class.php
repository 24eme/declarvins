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
	
  protected function getCompte()
  {
  	return acCouchdbManager::getClient('_Compte')->retrieveByLogin($_SERVER['PHP_AUTH_USER']);
  }
	
  protected function securizeInterpro($interpro)
  {
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
  	$interpros = array_keys($this->getCompte()->interpro->toArray());
  	if (!in_array($interpro, $interpros)) {
  		throw new error401Exception("Accès restreint");
  	}
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

  public function executeStreamDAIDS(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $daids = DAIDSDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'));
    return $this->renderCsv($daids->rows, DAIDSDateView::VALUE_DATEDESAISIE, "DAIDS", $dateTime->format('c'));
  }
  
  public function executeStreamVrac(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $vracs = $this->vracCallback(VracDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows);
    return $this->renderCsv($vracs, VracDateView::VALUE_DATE_SAISIE, "VRAC", $dateTime->format('c'));
  }
  
  public function executeStreamDRM(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
  	$this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $drms = DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'), true);
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $dateTime->format('c'));
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
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'));
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
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'));
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
    	$dateForView->modify('-1 second')->format('c');
    }
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $drms = DRMEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView);
    return $this->renderCsv($drms->rows, DRMEtablissementView::VALUE_DATEDESAISIE, "DRM", $date);
  }
  
  public function executePushDRMEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);  	
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
	$formUploadCsv = new UploadCSVForm();
    $result = array();
	if ($request->isMethod('post')) {
    	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
      	if ($formUploadCsv->isValid()) {
			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
	      	$lignes = $csv->getCsv();
		    $drmClient = DRMClient::getInstance();
		    $numLigne = 0;
		  	foreach ($lignes as $ligne) {
		    	$numLigne++;
		    	$import = new DRMDetailImport($ligne, $drmClient);
		    	$drm = $import->getDrm();
		    	if ($import->hasErrors()) {
		    		$result[$numLigne] = array('ERREUR', 'LIGNE', $numLigne, implode(' - ', $import->getLogs()));
		    	} else {
		    		$result[$numLigne] = array('OK', '', $numLigne, '');
		    		$drm->save();
		    	}
		    }
      	} else {
      		$result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
      	}
      	
    } else {
    	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    }
    return $this->renderSimpleCsv($result);
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
    $vracs = $this->vracCallback(VracEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView)->rows);
    return $this->renderCsv($vracs, VracEtablissementView::VALUE_DATE_SAISIE, "VRAC", $date);
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
    return $this->renderSimpleCsv($result);
  }
  
  public function executeStreamStatistiquesBilanDrm(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{4}/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $bilan = new StatistiquesBilan($interpro, $campagne);
    
    $csv_file = 'Identifiant;Raison Sociale;Nom Com.;Adresse;Code postal;Commune;Pays;Email;Tel.;Fax;Douane;';
    foreach ($bilan->getPeriodes() as $periode){
    	$csv_file .= "$periode;";
    }
	$csv_file .= "\n";		
    $etablissementsInformations = $bilan->getEtablissementsInformations();
    $drmsInformations = $bilan->getDRMsInformations();
    foreach ($bilan->getEtablissementsInformations() as $identifiant => $etablissement) {
		$informations = $etablissementsInformations[$identifiant];
		$csv_file .= $identifiant.';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_EMAIL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_TELEPHONE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_FAX].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_SERVICE_DOUANE].';';
		$drms = $drmsInformations[$identifiant];
		$precedente = null;
		foreach ($bilan->getPeriodes() as $periode) {
    			if (!isset($drms[$periode]) && !$precedente) {
    				$first = DRMAllView::getInstance()->getFirstDrmPeriodeByEtablissement($identifiant); 
    				if($first && $periode < $first)
    					$csv_file .= ';';
    				else 
    					$csv_file .= '0;';
    			} elseif (!isset($drms[$periode]) && $precedente && $precedente[StatistiquesBilanView::VALUE_DRM_TOTAL_FIN_DE_MOIS] > 0)
    			$csv_file .= '0;';
    			elseif (isset($drms[$periode]) && !$drms[$periode][StatistiquesBilanView::VALUE_DRM_DATE_SAISIE])
    			$csv_file .= '0;';
    			else
    			$csv_file .= '1;';
    			if (isset($drms[$periode])) {
    				$precedente = $drms[$periode];
    			}
		}
	$csv_file .= "\n";
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$campagne.".csv");
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
  
  protected function vracCallback($interpro, $items)
  {
  		$vracs = array();
  		$configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
  		foreach ($items as $item) {
  			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
  			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
  			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
  			$vracs[] = $item;
  		}
  		return $vracs;
  }

  protected function renderCsv($items, $dateSaisieIndice, $type, $date = null) 
  {
    $this->setLayout(false);
    $csv_file = '';
    $startDate = ($date)? $date."_" : '';
    $lastDate = $date;
    $beginDate = '9999-99-99';
    foreach ($items as $item) {
      		$csv_file .= implode(';', $item->value)."\n";
      	if ($lastDate < $item->value[$dateSaisieIndice]) {
      		$lastDate = $item->value[$dateSaisieIndice];
      	}
      	if ($beginDate > $item->value[$dateSaisieIndice]) {
      		$beginDate = $item->value[$dateSaisieIndice];
      	}
    }
    if (!$startDate) {
    	$startDate = $beginDate."_";
    }
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$type."_".$startDate.$lastDate.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText($csv_file);
  }
	
  protected function renderSimpleCsv($items, $date = null) 
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
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$lastDate."_resultat_import_drm.csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText(utf8_decode($csv_file));
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
