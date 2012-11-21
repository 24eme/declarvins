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

  private function renderCsvDRMs($drms, $date = null) {
    $this->setLayout(false);
    $csv_file = '';
    $lastdate = $date;
    foreach ($drms as $drm) {
      $csv_file .= $this->getComponent('edi', 'viewDRM', array('drm' => $drm));
      $lastdate = $drm->valide->date_saisie;
    }
    if (!$csv_file) {
	$this->response->setStatusCode(204);
	return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('LastDocDate', $lastdate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastdate)));
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

    return $this->renderCsvDRMs(array($drm));
  }

  public function executeStreamDRM(sfWebRequest $request) {
  	ini_set('memory_limit', '512M');
    $date = $request->getParameter('datedebut');
    if (!$date) {
	return $this->renderText("Pas de date définie");
    }
    if (preg_match('/^(.*T[0-9][0-9]:[0-9][0-9]:[0-9])([0-9])(.*)$/', $date, $match)) {
	$match[2] += 1;
	$date = $match[1].$match[2].$match[3];
    }
    $interpro = $request->getParameter('interpro');
    if (!preg_match('/^INTERPRO-/', $interpro)) {
	$interpro = 'INTERPRO-'.$interpro;
    }
    $drms = DRMClient::getInstance()->findByInterproDate($interpro, $date);
    return $this->renderCsvDRMs($drms, $date);
  }

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

  public function executeUploadEtablissements(sfWebRequest $request) {  
    $id = $request->getParameter('id');
    if (!$id) {
      return $this->redirect('edi/uploadEtablissements?id=INTERPRO');
    }
    $this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($id));
    $this->formUploadCsv = new UploadCSVForm();
    echo $this->formUploadCsv->getName();
    if ($request->isMethod(sfWebRequest::POST)) {
      $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
      if ($this->formUploadCsv->isValid()) {
	$file = $this->formUploadCsv->getValue('file');
	$this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
	unlink($file->getSavedName());
	$this->getUser()->setFlash('notification_general', "Le fichier csv d'import a bien été uploadé");
	$this->redirect('edi/updateEtablissements?id='. $this->interpro->get('_id'));
      } 
    }
    $this->setTemplate('file');
  }
  
  public function executeUpdateEtablissements(sfWebRequest $request) {
    $this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));
    $import = new ImportEtablissementsCsv($this->interpro);
    $this->nb = $import->updateOrCreate();
    $this->setLayout(false);
    $this->response->setContentType('text/plain');
  }
}
