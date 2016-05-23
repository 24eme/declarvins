<?php

class drm_crdActions extends sfActions
{
	public function preExecute()
  	{
  		try {
  			$etablissement = $this->getRoute()->getEtablissement();
  		} catch (Exeption $e) {
  			$etablissement = null;
  		}
  		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement) {
  			$configuration = ConfigurationClient::getCurrent();
  			$access = ($configuration->isApplicationOuverte($etablissement->interpro, 'drm'))? true : false;
  			$this->forward404Unless($access);
  		}
  		
  	}
    public function executeIndex(sfWebRequest $request) {
    	set_time_limit(60);
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        
        $this->form = new DRMCrdsForm($this->drm);
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
        		$this->drm = $this->form->save();
        		if ($request->isXmlHttpRequest()) {
        			return $this->renderText(json_encode(array("success" => true)));
        		} else {
	        		if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
	                	$this->drm->setCurrentEtapeRouting('validation');
	                	$this->redirect('drm_validation', $this->drm);
	        		} else {
		        		$this->drm->setCurrentEtapeRouting('declaratif');
		        		$this->redirect('drm_declaratif', $this->drm);
	        		}
        		}
        	}
        }
    }
    
    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
    	$drm = $this->getRoute()->getDRM();
        $config = ConfigurationClient::getCurrent();
        
        $form = new DRMCrdAjoutForm($drm, $config);
        if ($request->isMethod(sfWebRequest::POST)) {
    		$this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
                $drm = $form->addCrd();
                $drm->save();
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_crd', $drm))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form)))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form)));
    }
}