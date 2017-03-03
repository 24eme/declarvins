<?php

class drm_vracActions extends sfActions
{
	public function preExecute()
  	{
  		try {
  			$etablissement = $this->getRoute()->getEtablissement();
  		} catch (Exeption $e) {
  			$etablissement = null;
  		}
  		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement) {
  			$configuration = ConfigurationClient::getCurrent();
  			$access = ($configuration->isApplicationOuverte($etablissement->interpro, 'drm', $etablissement))? true : false;
  			$this->forward404Unless($access);
  		}
  		
  	}
    public function executeIndex(sfWebRequest $request) {
    	set_time_limit(60);
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->details = $this->drm->getDetailsAvecVrac();

        if($request->hasParameter('precedent')) {
                $this->redirectIfNoMouvementCheck();
                }

        if (count($this->details)==0) {
            if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
                        $this->drm->setCurrentEtapeRouting('validation');
                return $this->redirect('drm_validation', $this->drm);
            }
            
            if ($this->getUser()->getCompte()->isTiers() && !$this->etablissement->isTransmissionCiel()) {
            	$this->drm->setCurrentEtapeRouting('declaratif');
            	return $this->redirect('drm_declaratif', $this->drm);
            }
                        return $this->redirect('drm_crd', $this->drm);
        }

            $this->drm->etape = "vrac";
            $interpro = null;
            if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            	$interpro = $this->getUser()->getCompte()->getGerantInterpro()->_id;
            }
                $this->form = new DRMVracForm($this->drm, $interpro);

        if ($request->isMethod(sfWebRequest::POST)) {

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                        $this->drm = $this->form->save();
                        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
                                $this->drm->setCurrentEtapeRouting('validation');
                                return $this->redirect('drm_validation', $this->drm);
                        }
                        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
                        	$this->drm->setCurrentEtapeRouting('validation');
                			return $this->redirect('drm_validation', $this->drm);
           				}
            
            			if ($this->getUser()->getCompte()->isTiers() && !$this->etablissement->isTransmissionCiel()) {
            				$this->drm->setCurrentEtapeRouting('declaratif');
            				return $this->redirect('drm_declaratif', $this->drm);
            			}

            			$this->drm->setCurrentEtapeRouting('crd');
                        return $this->redirect('drm_crd', $this->drm);
                }
        }
    }


    protected function redirectIfNoMouvementCheck() {    	
    	if ($this->drm->hasVrac()) {
    		return;
    	}
    	if (!$this->drm->detailHasMouvementCheck()) {
	    	return $this->redirect('drm_mouvements_generaux', $this->drm);
    	}
    	$last_certification = null;
        if(count($this->drm->declaration->certifications) > 0) {        
        	foreach ($this->drm->declaration->certifications as $key => $certification) {
        		if (count($this->drm->declaration->certifications->get($key)->genres) > 0) {
        			$last_certification = $this->drm->declaration->certifications->get($key);
        		}
        	}
        }
    	return $this->redirect('drm_recap', $last_certification);
    }
}