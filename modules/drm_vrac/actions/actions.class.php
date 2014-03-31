<?php

class drm_vracActions extends sfActions
{
    
    public function executeIndex(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->details = $this->drm->getDetailsAvecVrac();
    	
    	if($request->hasParameter('precedent')) {
    		$this->redirectIfNoMouvementCheck();
	   	}
	   	
	    if ($this->drm->hasVersion()) {
    		foreach ($this->drm->getDetails() as $detail) {
    			if (count($detail->vrac) > 0 && !$detail->sorties->vrac) {
	    			foreach ($detail->vrac as $numero => $vrac) {
	    				$volume = $vrac->volume;
						$contrat = VracClient::getInstance()->findByNumContrat($numero);
						if ($contrat->isSolde() && $volume > 0) {
							$contrat->desolder();
						}
						$contrat->soustraitVolumeEnleve($volume);
						$contrat->save(false);
	    			}
    			} elseif (count($detail->vrac) > 0) {
    				$volume = 0;
	    			foreach ($detail->vrac as $numero => $vrac) {
	    				$volume += $vrac->volume; 
	    			}
	    			if($volume != $detail->sorties->vrac) {
	    				foreach ($detail->vrac as $numero => $vrac) {
		    				$volume = $vrac->volume;
							$contrat = VracClient::getInstance()->findByNumContrat($numero);
							if ($contrat->isSolde() && $volume > 0) {
								$contrat->desolder();
							}
							$contrat->soustraitVolumeEnleve($volume);
							$contrat->save(false);
	    				}
	    			}
    			}
    		}
	    }
	    
    	if (count($this->details)==0) {
            if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    			$this->drm->setCurrentEtapeRouting('validation');
                return $this->redirect('drm_validation', $this->drm);
            }
	  		return $this->redirect('drm_declaratif', $this->drm);
    	}

    	$this->drm->etape = "vrac";
		$this->form = new DRMVracForm($this->drm);

    	if ($request->isMethod(sfWebRequest::POST)) {

    		$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
       			$this->form->save();
       			$this->drm->setCurrentEtapeRouting('declaratif');
		        if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
		        	$this->drm->setCurrentEtapeRouting('validation');
		          	return $this->redirect('drm_validation', $this->drm);
		        }
	        	return $this->redirect('drm_declaratif', $this->drm);
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