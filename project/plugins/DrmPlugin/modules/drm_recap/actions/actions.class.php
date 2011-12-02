<?php

class drm_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->forward('drm_recap', 'appellation');
    }
    
    public function executeAppellationAjoutAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        
        $drm = $this->getUser()->getDrm();
        $this->label = $this->getRoute()->getObject();

        $this->form = new DRMAppellationAjoutForm($drm->declaration->labels->add($this->label->getKey())->appellations);
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap_appellation', $this->form->getAppellation()))));
            }
        }

        return $this->renderText(json_encode(array("success" => false, 
                                                       "content" => $this->getPartial('drm_recap/popupAppellationAjout', array('label' => $this->label, 'form' => $this->form)))));
    }
    
    public function executeAppellation(sfWebRequest $request) {
        $this->init();
    }
    
    public function executeAjout(sfWebRequest $request) {
        
        $this->init();
        $this->drm_appellation->couleurs->add('rouge')->details->add();

        $this->setTemplate('appellation');
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDrmDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
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
