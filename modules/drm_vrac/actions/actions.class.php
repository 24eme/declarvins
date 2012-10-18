<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->details = $this->drm->getDetailsAvecVrac();
    	
    	/*
    	 * Si il n'y a pas de sortie vrac declaree, on redirige sur l'etape suivante
    	 */
    	if (count($this->details)==0) {
    	  	if($request->hasParameter('precedent')) {
    	    	return $this->redirect('drm_recap', $this->drm->declaration->certifications->getLast());
	    	}
            if ($this->drm->mode_de_saisie == DRM::MODE_DE_SAISIE_PAPIER) {
    			$this->drm->setCurrentEtapeRouting('validation');
                return $this->redirect('drm_validation', $this->drm);
            }
	  		return $this->redirect('drm_declaratif', $this->drm);
    	}

    	$this->drm->setCurrentEtapeRouting('vrac');
		$this->form = new DRMVracForm($this->drm);

    	if ($request->isMethod(sfWebRequest::POST)) {
    		$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
       			$this->form->save();
       			$this->drm->setCurrentEtapeRouting('declaratif');
		        if ($this->drm->mode_de_saisie == DRM::MODE_DE_SAISIE_PAPIER) {
		          return $this->redirect('drm_validation', $this->drm);
		        }
	        	return $this->redirect('drm_declaratif', $this->drm);
        	}
    	}
    }
}