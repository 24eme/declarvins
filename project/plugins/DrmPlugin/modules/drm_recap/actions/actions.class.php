<?php

class drm_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->init();

        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->drm->setCurrentEtapeRouting('vrac');
        	$this->redirect('drm_vrac', $this->drm);
        }
        $this->setTemplate('appellation');
    }
    
    public function executeAppellationAjoutAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $this->certification = $this->getRoute()->getObject();
        $this->drm = $this->getRoute()->getDrm();

        $this->form = new DRMAppellationAjoutForm($this->drm->produits->add($this->certification->getKey()),
                                                  $this->getUser()->getTiers()->interpro);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                return $this->renderText(json_encode(array("success" => true,
                                                           "ajax" => true,
                                                           "url" => $this->generateUrl('drm_recap_ajout_ajax', $this->certification->appellations->get($this->form->getAppellation()->getKey())))));
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

        $form = new DRMProduitAjoutForm($this->drm_appellation->getProduits()->add(),
                                        $this->getUser()->getTiers()->interpro);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $form->save();
                $this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap_appellation', $this->drm_appellation))));
            }
            return $this->renderText(json_encode(array("success" => false, 
                                                       "content" => $this->getPartial('formAjout', array('form' => $form)))));
        }
        return $this->renderPartial('popupAjout', array('form' => $form));
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
        $this->drm = $this->getRoute()->getDrm();
        $this->config_appellation = $this->getRoute()->getConfigAppellation();
        $this->drm_appellation = $this->getRoute()->getDrmAppellation();
    }
    
}
