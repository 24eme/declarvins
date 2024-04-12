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
    	set_time_limit(0);
        $this->formUploadCsv = new UploadCSVForm();
		$this->hasErrors = false;
        if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
	        $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
	          if ($this->formUploadCsv->isValid()) {
	            $file = $this->formUploadCsv->getValue('file');
	            $this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
	            unlink($file->getSavedName());
	            $this->import = new ImportEtablissementsCsv($this->interpro);

	            $nb = $this->import->updateOrCreate();
	            $this->getUser()->setFlash('notice', "$nb établissements ont été importés");
	            if (! count($this->import->getErrors())) {
                    $this->redirect('interpro_upload_csv', array('id' => $this->interpro->get('_id')));
	            }
            }
        }
    }
    
	public function executeUploadCsvVolumesBloques(sfWebRequest $request) {  
    	$this->forward404Unless($this->interpro = $this->getUser()->getCompte()->getGerantInterpro());  
    	ini_set('memory_limit', '1024M');
    	set_time_limit(120);   
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
    
    public function executeUploadCsvVracPrix(sfWebRequest $request) {
    	$this->forward404Unless($this->interpro = $this->getUser()->getCompte()->getGerantInterpro());
    	ini_set('memory_limit', '1024M');
    	set_time_limit(0);
    	$this->formUploadCsv = new UploadCSVForm();
    	$this->hasErrors = false;
    	if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
    		$this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
    		if ($this->formUploadCsv->isValid()) {
    			$file = $this->formUploadCsv->getValue('file');
    			
    			
    			$csv = array();
    			if (@file_get_contents($file->getSavedName())) {
    				$handler = fopen($file->getSavedName(), 'r');
    				if (!$handler) {
    					throw new Exception('Cannot open csv file anymore');
    				}
    				while (($line = fgetcsv($handler, 0, ";")) !== FALSE) {
    					if (!preg_match('/^#/', trim($line[ImportVracPrixCsv::COL_VISA]))) {
    						$csv[] = $line;
    					}
    				}
    				fclose($handler);
    			}
    			
    			$this->import = new ImportVracPrixCsv($csv);
    			try {
    				$nb = $this->import->update();
    				$this->getUser()->setFlash('notice', "$nb contrats ont été mis à jour");
    				$this->redirect('interpro_upload_csv_vrac_prix');
    			} catch (Exception $e) {
    				$this->hasErrors = true;
    			}
    		}
    	}
    }
}
