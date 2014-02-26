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
    	ini_set('memory_limit', '1024M');
    	set_time_limit(60);
        $this->formUploadCsv = new UploadCSVForm();
		$this->hasErrors = false;
        if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
	        $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
	          if ($this->formUploadCsv->isValid()) {
	            $file = $this->formUploadCsv->getValue('file');
	            $this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
	            unlink($file->getSavedName());
	            $this->import = new ImportEtablissementsCsv($this->interpro);
	            
	            try {
	            	$nb = $this->import->updateOrCreate();
        			$this->getUser()->setFlash('notice', "$nb établissements ont été importés");
	            	$this->redirect('interpro_upload_csv', array('id' => $this->interpro->get('_id')));
	            } catch (Exception $e) {
	            	$this->hasErrors = true;
	            }
            }
        }
    }
    
	public function executeUploadCsvVolumesBloques(sfWebRequest $request) {  
    	$this->forward404Unless($this->interpro = $this->getUser()->getCompte()->getGerantInterpro());  
    	ini_set('memory_limit', '1024M');
    	set_time_limit(60);   
        $this->formUploadCsv = new UploadCSVForm();
		$this->hasErrors = false;
        if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
	        $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
	          if ($this->formUploadCsv->isValid()) {
	            $file = $this->formUploadCsv->getValue('file');
	            $this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'volumes-bloques.csv');
	            unlink($file->getSavedName());
	            $this->import = new ImportVolumesBloquesCsv($this->interpro);
	            try {
		            $nb = $this->import->updateOrCreate();
	        		$this->getUser()->setFlash('notice', "$nb volumes bloqués ont été importés");
		            $this->redirect('interpro_upload_csv_volumes_bloques');
	          	} catch (Exception $e) {
	            	$this->hasErrors = true;
	            }
            }
        }
    }
}
