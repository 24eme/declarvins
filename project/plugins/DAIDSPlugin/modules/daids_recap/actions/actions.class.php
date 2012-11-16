<?php

class daids_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) 
    {
        $this->init();
        $this->setTemplate('index');
    }

    public function executeRedirectIndex(sfWebRequest $request) 
    {
    	$daids = $this->getRoute()->getDAIDS();
    	$first_certification = null;
        if(count($daids->declaration->certifications) > 0) {
            $first_certification = $daids->declaration->certifications->getFirst();
        }
        $this->redirect('daids_recap', $first_certification);
    }
    
    protected function init() 
    {
        $this->form = null;
        $this->detail = null;
        $this->daids = $this->getRoute()->getDAIDS();
        $this->config_lieu = $this->getRoute()->getConfigLieu();
        $this->daids_lieu = $this->getRoute()->getDAIDSLieu();
        $this->produits = $this->daids_lieu->getProduits();
        $this->previous = $this->daids_lieu->getPreviousSister();
        $this->next = $this->daids_lieu->getNextSister();
    	$this->previous_certif = $this->daids_lieu->getCertification()->getPreviousSister();
    	$this->next_certif = $this->daids_lieu->getCertification()->getNextSister();
    }
    
    public function executeLieu(sfWebRequest $request) 
    {
        $this->init();
        $this->setTemplate('index');
    }
    
    /*
     * A REVOIR
     */
    
    
    
    public function executeDetail(sfWebRequest $request) {
        $this->init();
        $this->detail = $this->getRoute()->getDRMDetail();
        $this->setTemplate('index');
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDRMDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
        	$this->form->save();
            if ($request->isXmlHttpRequest()) {
				         		
                return $this->renderText(json_encode(array("success" => true,
                										   "content" => "",
                										   "document" => array("id" => $this->drm->get('_id'),
                										   					   "revision" => $this->drm->get('_rev'))
                										   )));
            } else {
                $this->redirect('drm_recap_lieu', $this->config_lieu);
            }
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/itemFormErrors', array('form' => $this->form)))));
        } else {
            $this->setTemplate('index');
        }
    }
}
