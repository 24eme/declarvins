<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDrm();
    	$this->details = $this->drm->getDetailsAvecVrac();

    	if (count($this->details)==0) {
        if($request->hasParameter('precedent')) {
          $this->redirect('drm_recap_appellation', $this->drm->produits->getLast()->getLast()->getDeclaration());
        } else {
          $this->redirect('drm_declaratif', $this->drm);
        }
    	}

    	$this->forms = array();
	$this->noContrats = array();

    	foreach ($this->details as $detail) {
    	  $contrats = $detail->getContratsVrac();
    	  if (count($contrats)==1) {
    	    $contratVrac = $detail->addVrac($contrats[0]->numero, $detail->sorties->vrac);
    	    $detail->getDocument()->save();
    	  }
	  if (!count($contrats)) {
	    $this->noContrats[$detail->getIdentifiantHTML()] = true;
	  }

    	  foreach ($detail->getVrac() as $vrac) {
    	    $this->forms[$detail->getIdentifiantHTML()][$vrac->getKey()] = new VracDetailModificationForm($vrac);
    	  }
    	}
    }
    
    public function executeDeleteVrac(sfWebRequest $request) {
    	$vrac = $this->getRoute()->getObject();
    	$vrac->getParent()->remove($vrac->getKey());
    	$vrac->getDocument()->save();
    	$this->redirect('drm_vrac', $this->getRoute()->getDrm());
    	
    }
    
    public function executeNouveauContrat(sfWebRequest $request) {
      $this->forward404Unless($request->isXmlHttpRequest());
      $form = new VracAjoutContratForm($this->getRoute()->getObject());
      if (!$request->isMethod(sfWebRequest::POST)) {
	return $this->renderText($this->getPartial('ajoutContratForm', array('form' => $form)));
      }
      $this->getResponse()->setContentType('text/json');
      $form->bind($request->getParameter($form->getName()));
      if (!$form->isValid()) {
	return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form)))));
      }
      $form->save();
      $this->getUser()->setFlash("notice", 'Le contrat a été ajouté avec success.');
      return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_vrac', $this->getRoute()->getDrm()))));
    }
    
    public function executeUpdateVolume(sfWebRequest $request) {
      if ($request->isMethod(sfWebRequest::POST)) {
	$form = new VracDetailModificationForm($this->getRoute()->getObject());
	$form->bind($request->getParameter($form->getName()));
	if ($form->isValid()) {
	  $form->save();
	}
      } 
      $this->redirect('drm_vrac', $this->getRoute()->getDrm());
    }
}