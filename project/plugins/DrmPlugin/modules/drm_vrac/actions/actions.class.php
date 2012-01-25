<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
    	$this->details = $this->getUser()->getDrm()->getDetailsAvecVrac();
    }
    
    public function executeNouveauContrat(sfWebRequest $request) {
    	if ($request->isXmlHttpRequest()) {
        	$this->getResponse()->setContentType('text/json');        	
            $form = new VracAjoutContratForm($this->getRoute()->getObject());
            if ($request->isMethod(sfWebRequest::POST)) {
	            $form->bind($request->getParameter($form->getName()));
				if ($form->isValid()) {
					$form->save();
					$this->getUser()->setFlash("notice", 'Le contrat a été ajouté avec success.');
					return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('vrac'))));
				}
            }
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('ajoutContratForm', array('form' => $form)))));
        } else {
            return sfView::NONE;
        }
    }
    
    
}
