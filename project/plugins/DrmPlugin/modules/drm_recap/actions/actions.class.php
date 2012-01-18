<?php

class drm_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->init();
        $this->setTemplate('appellation');
    }
    
    public function executeAppellationAjoutAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $drm = $this->getUser()->getDrm();
        $this->label = $this->getRoute()->getObject();

        $this->form = new DRMAppellationAjoutForm($drm->declaration->certifications->add($this->label->getKey())->appellations);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_recap_appellation', $this->form->getAppellation()))));
            }
        }
		
        return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/popupAppellationAjout', array('label' => $this->label, 'form' => $this->form)))));
    }
    
    public function executeAppellation(sfWebRequest $request) {
        $this->init();
    }
    
    public function executeAjoutAjax(sfWebRequest $request) {
        $this->init();
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $drm = $this->getUser()->getDrm();
        $form = new DRMMouvementsGenerauxProduitAjoutForm(
            $drm->produits->add($this->drm_appellation->getCertification()->getKey())
                          ->add($this->drm_appellation->getKey())
                          ->add()
        );
        if ($request->isMethod(sfWebRequest::POST)) {
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $form->save();
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap_appellation', $this->config_appellation))));
            }
        }
        return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('popupAjout', array('form' => $form, 'config_appellation' => $this->config_appellation)))));
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDrmDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
        	$this->form->getObject()->getDocument()->update();
            $this->form->save();
            if ($request->isXmlHttpRequest()) {
                return $this->renderText(json_encode(array("success" => true, "content" => "")));
            } else {
                $this->redirect('drm_recap_appellation', $this->config_appellation);
            }
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/itemFormErrors', array('form' => $this->form)))));
        } else {
            $this->setTemplate('appellation');
        }
    }
    
    protected function init() {
        $this->form = null;
        $this->form_appellation_ajout = null;
        $this->config_appellation = $this->getRoute()->getConfigAppellation();
        $this->drm_appellation = $this->getRoute()->getDrmAppellation();
    }
    
}
