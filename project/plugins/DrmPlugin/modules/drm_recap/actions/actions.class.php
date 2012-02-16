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
        $this->certification = $this->getRoute()->getObject();

        $this->form = new DRMAppellationAjoutForm($drm->produits->add($this->certification->getKey()));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->renderText(json_encode(array("success" => true,
                                                           "ajax" => true,
                                                           "url" => $this->generateUrl('drm_recap_ajout_ajax', array('certification' => 'AOP')))));
            }

            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/formAppellationAjout', array('certification' => $this->certification, 'form' => $this->form)))));
        }
		
        return $this->renderText($this->getPartial('drm_recap/popupAppellationAjout', array('certification' => $this->certification, 'form' => $this->form)));
    }
    
    public function executeAppellation(sfWebRequest $request) {
        $this->init();
    }

    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->init();
        $this->forward404Unless($request->isXmlHttpRequest());
        $drm = $this->getUser()->getDrm();
        $form = new DRMProduitAjoutForm($drm->produits->get($this->config_appellation->getCertification()->getKey())
                                                      ->get($this->config_appellation->getKey())
                                                      ->add(),
                                        'INTERPRO-inter-rhone');

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $form->save();
                $this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap', $this->config_appellation))));
            }
            return $this->renderText(json_encode(array("success" => false, 
                                                       "content" => $this->getPartial('form', array('form' => $form, 
                                                                                                    'config_appellation' => $this->config_appellation)))));
        }
        return $this->renderPartial('popupAjout', array('form' => $form, 'config_appellation' => $this->config_appellation));
    }
    
    public function executeDetail(sfWebRequest $request) {
        $this->init();
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
        //$this->form_appellation_ajout = null;
        $this->config_appellation = $this->getRoute()->getConfigAppellation();
        $this->drm_appellation = $this->getRoute()->getDrmAppellation();
    }
    
}
