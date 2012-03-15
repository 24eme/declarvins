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
	return $this->redirect('edi/csvView?md5=' . $this->formUploadCsv->getValue('file')->getMd5());
      }
    }
  }

  public function executeCsvView(sfWebRequest $request) 
  {
    $this->response->setContentType('text/plain');
    $md5 = $request->getParameter('md5');
    set_time_limit(600);
    $csv = new DRMCsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $md5);
    $this->iddrm = null;
    try {
      $drm = $csv->importDRM($this->getUser()->getTiers()->identifiant);
      $drm->save();
      $this->iddrm = $drm->_id;
    }catch(sfException $e) {
      $this->errors = $csv->getErrors();
      if (!count($this->errors))
	throw $e;
    }
    
    $this->setLayout(false);
  }
  public function executeListDRM(sfWebRequest $request) 
  {
    $this->setLayout(false);
    $this->response->setContentType('text/plain');

    $this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant);
  }
  public function executeListContrat(sfWebRequest $request) 
  {
    $this->setLayout(false);
    $this->response->setContentType('text/plain');
    
    $this->contrats = VracClient::getInstance()->retrieveFromEtablissements($this->getUser()->getTiers()->identifiant);
  }
}
