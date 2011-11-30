<?php

class drm_recapActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->appellation = $this->getRoute()->getObject();
    }
    
    public function executeAppellationAjout(sfWebRequest $request) {
        $drm = $this->getUser()->getDrm();
        $this->label = $this->getRoute()->getObject();

        $this->form = new DRMAppellationAjoutForm($drm->declaration->labels->add($this->label->getKey())->appellations);
        
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('drm_recap_appellation_ajout', $this->label);
            }
        }
    }
    
}
