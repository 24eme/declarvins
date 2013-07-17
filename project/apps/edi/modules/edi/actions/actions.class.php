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
  public function executeFile(sfWebRequest $request)
  {
    $this->formUploadCsv = new UploadCSVForm();
    
    if ($request->isMethod('post')) {
      $this->formUploadCsv->bind($request->getParameter($this->formUploadCsv->getName()), $request->getFiles($this->formUploadCsv->getName()));
      if ($this->formUploadCsv->isValid()) {
	return $this->redirect('edi/'.$request->getParameter('csvViewer').'?md5=' . $this->formUploadCsv->getValue('file')->getMd5());
      }
    }
  }

  public function executeCsvDRMView(sfWebRequest $request) 
  {
    $this->response->setContentType('text/plain');
    $md5 = $request->getParameter('md5');
    set_time_limit(600);

    $csv = new DRMCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
    $this->iddrm = null;
    try {
      $drm = $csv->importDRM(array('compte' => $this->getUser()));
      $drm->mode_de_saisie = 'EDI';
      $drm->save();
      $this->iddrm = $drm->_id;
    }catch(sfException $e) {
      $this->errors = $csv->getErrors();
      if (!count($this->errors))
	throw $e;
    }
    
    $this->setLayout(false);
  }
  public function executeCsvContratView(sfWebRequest $request) 
  {
    $this->response->setContentType('text/plain');
    $md5 = $request->getParameter('md5');
    set_time_limit(600);
    $csv = new VracCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
    $contrats = $csv->importContrats();
    $this->nb = count($contrats);
    $this->errors = $csv->getErrors();
    foreach($contrats as $c) {
      $c->save();
    }
    $this->setLayout(false);
  }
  
  /*
   * FONCTION REVUES ET CORRIGEES
   */
  public function executeStreamDAIDS(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $daids = DAIDSDateView::getInstance()->findByInterproAndDate($interpro, $dateTime->format('c'));
    return $this->renderCsv($daids->rows, DAIDSDateView::VALUE_DATEDESAISIE, $dateTime->format('c'));
  }
  
  public function executeStreamVrac(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $vracs = VracDateView::getInstance()->findByInterproAndDate($interpro, $dateTime->format('c'));
    return $this->renderCsv($vracs->rows, VracDateView::VALUE_DATE_SAISIE, $dateTime->format('c'));
  }
  
  public function executeStreamDRM(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $drms = DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateTime->format('c'));
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, $dateTime->format('c'));
  }
  
  public function executeStreamStatistiquesBilanDrm(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '1024M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
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
    
    $csv_file = 'Etablissements;';
    foreach ($bilan->getPeriodes() as $periode){
    	$csv_file .= "$periode;";
    }
	$csv_file .= "\n";		
    $etablissementsInformations = $bilan->getEtablissementsInformations();
    $drmsInformations = $bilan->getDRMsInformations();
    foreach ($bilan->getEtablissementsInformations() as $identifiant => $etablissement) {
		$informations = $etablissementsInformations[$identifiant];
		$csv_file .= $identifiant.';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_RAISON_SOCIALE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_NOM].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_ADRESSE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_CODE_POSTAL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_COMMUNE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_PAYS].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_EMAIL].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_TELEPHONE].';'.$informations[StatistiquesBilanView::VALUE_ETABLISSEMENT_FAX].';';
		$drms = $drmsInformations[$identifiant];
		$precedente = null;
		foreach ($bilan->getPeriodes() as $periode) {
    			if (!isset($drms[$periode]) && !$precedente)
    			$csv_file .= '0;';
    			elseif (!isset($drms[$periode]) && $precedente && $precedente[StatistiquesBilanView::VALUE_DRM_TOTAL_FIN_DE_MOIS] > 0)
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

  private function renderCsv($items, $dateSaisieIndice, $date = null) 
  {
    $this->setLayout(false);
    $csv_file = '';
    $lastDate = $date;
    foreach ($items as $item) {
      	$csv_file .= implode(';', $item->value)."\n";
      	if ($lastDate < $item->value[$dateSaisieIndice]) {
      		$lastDate = $item->value[$dateSaisieIndice];
      	}
    }
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$lastDate.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText($csv_file);
  }
  
  public function executeViewDRM(sfWebRequest $request) 
  {
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

  // A TESTER
  public function executeUploadEtablissements(sfWebRequest $request) 
  {  
  	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));
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
  /*
   * // FIN
   * 
   */

  public function executeRedirect(sfWebRequest $request) {
	$param = $request->getGetParameters();
	unset($param['destination']);
	return $this->redirect(array_merge(array("sf_route" => $request->getParameter('destination')), $param));
  }

  public function executeListDRM(sfWebRequest $request) 
  {
    $this->setLayout(false);
    $this->response->setContentType('text/plain');
    $this->historiques = array();
    $compte = $this->getUser()->getCompte();
    foreach($this->getUser()->getCompte()->getTiersCollection() as $tiers) {
      $this->historiques[] = new DRMHistorique($tiers->identifiant);
    }
  }
  public function executeListContrat(sfWebRequest $request) 
  {
    $this->setLayout(false);
    $this->response->setContentType('text/plain');
    
    $this->contrats = array();
    foreach ($this->getUser()->getCompte()->getTiersCollection() as $tiers) {
      	$this->contrats = array_merge($this->contrats, VracClient::getInstance()->retrieveActifFromEtablissements($tiers->identifiant));
    }
  }
}
