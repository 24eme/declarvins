<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
    	$this->details = $this->getUser()->getDrm()->getDetailsAvecVrac();
		$this->forms = array();
		foreach ($this->details as $detail) {
			foreach ($detail->getVrac() as $vrac) {
				$this->forms[$detail->getIdentifiant()][$vrac->getKey()] = new VracDetailModificationForm($vrac);
			}
		}
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
    
    public function executeUpdateVolume(sfWebRequest $request) {
    	if ($request->isMethod(sfWebRequest::POST)) {
			$form = new VracDetailModificationForm($this->getRoute()->getObject());
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
               $form->save();
            }
        } 
        $this->redirect('drm_vrac');
    }
}