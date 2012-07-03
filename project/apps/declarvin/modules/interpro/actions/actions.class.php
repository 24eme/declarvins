<?php

/**
 * interpro actions.
 *
 * @package    declarvin
 * @subpackage interpro
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class interproActions extends sfActions
{

    public function executeUploadCsv(sfWebRequest $request) {  
    	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));     
        $this->formUploadCsv = new UploadCSVForm();

        if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
	        $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
	          if ($this->formUploadCsv->isValid()) {
	            $file = $this->formUploadCsv->getValue('file');
	            $this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
	            unlink($file->getSavedName());
	            $this->getUser()->setFlash('notice', "Le fichier csv d'import a bien été uploadé");
	            $this->redirect('interpro_upload_csv', array('id' => $this->interpro->get('_id')));
            }
        }
    }

    public function executeUpdateEtablissements(sfWebRequest $request) {
    	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));
        $import = new ImportEtablissementsCsv($this->interpro);
        $nb = $import->updateOrCreate();

        $this->getUser()->setFlash('notice', "$nb établissements ont été importés");
        $this->redirect('interpro_upload_csv', array('id' => $this->interpro->get('_id')));
    }
}
