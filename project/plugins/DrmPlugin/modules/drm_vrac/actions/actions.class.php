<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
    	$this->details = $this->getUser()->getDrm()->getDetailsAvecVrac();
    	if (count($this->details)==0) {
    		$this->redirect('drm_validation');
    	}
		$this->forms = array();
		foreach ($this->details as $detail) {
			$contrats = $detail->getContratsVrac();
			if (count($contrats)==1) {
				$contratVrac = $contrats[0];
				$contratVrac = $detail->vrac->add($contratVrac->numero);
				$contratVrac->volume = $detail->sorties->vrac;
				$detail->getDocument()->save();
			}
			foreach ($detail->getVrac() as $vrac) {
				$this->forms[$detail->getIdentifiant()][$vrac->getKey()] = new VracDetailModificationForm($vrac);
			}
		}
    }
    
    public function executeNouveauContrat(sfWebRequest $request) {
    	if ($request->isXmlHttpRequest()) {        	
            $form = new VracAjoutContratForm($this->getRoute()->getObject());
            if ($request->isMethod(sfWebRequest::POST)) {
        		$this->getResponse()->setContentType('text/json');
	            $form->bind($request->getParameter($form->getName()));
				if ($form->isValid()) {
					$form->save();
					$this->getUser()->setFlash("notice", 'Le contrat a été ajouté avec success.');
					return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_vrac'))));
				} else {
					return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form)))));
				}
            }
            return $this->renderText($this->getPartial('ajoutContratForm', array('form' => $form)));
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