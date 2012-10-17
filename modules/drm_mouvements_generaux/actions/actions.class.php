<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$this->drm = $this->getRoute()->getDRM();
		$this->certifs = array();
		$this->certificationLibelle = array();
		$configuration = ConfigurationClient::getCurrent();
		$this->form = new DRMMouvementsGenerauxForm($configuration, $this->drm);
		foreach ($configuration->declaration->certifications as $certification_key => $certification_config) {
			if ($certification_config->hasProduit($this->drm->getInterpro()->get('_id'), $this->drm->getDepartement())) {
	            if (!isset($this->certifs[$certification_key])) {
					$this->certifs[$certification_key] = $certification_config->hasUniqProduit($this->drm->getInterpro()->get('_id'));
					$this->certificationLibelle[$certification_key] = $certification_config->libelle;
				}
			}
		}
        $this->first_certification = null;
        if(count($this->drm->declaration->certifications) > 0) {
            $this->first_certification = $this->drm->declaration->certifications->getFirst();
        }
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
       			$this->form->save();
	            if($this->drm->declaration->hasMouvementCheck()) {
	        	   $this->drm->setCurrentEtapeRouting('recapitulatif');
	               return $this->redirect('drm_recap', $this->first_certification);
	            } else {
	               if ($this->drm->mode_de_saisie == DRM::MODE_DE_SAISIE_PAPIER) {
	                   $this->drm->setCurrentEtapeRouting('validation');
	                   return $this->redirect('drm_validation', $this->drm); 
	               }
	               $this->drm->setCurrentEtapeRouting('declaratif');
	               return $this->redirect('drm_declaratif', $this->drm);
	            }
        	}
        }
    }

    public function executeStockEpuise(sfWebRequest $request) 
    {
    	$drm = $this->getRoute()->getDRM();
    	$this->forward404Unless($drm->declaration->hasStockEpuise());
        foreach($drm->declaration->getProduits() as $produit) {
        	$produit->pas_de_mouvement_check = 1;
        }

        if ($this->drm->mode_de_saisie == DRM::MODE_DE_SAISIE_PAPIER) {
            $drm->setCurrentEtapeRouting('validation');
        
            return $this->redirect('drm_validation', $drm);
        }

        $drm->setCurrentEtapeRouting('declaratif');
        
        return $this->redirect('drm_declaratif', $drm);
    }
    
    public function executeDelete(sfWebRequest $request) 
    {
		$objectToDelete = $this->getRoute()->getObject()->cascadingDelete();
		$objectToDelete->delete();
		$drm = $this->getRoute()->getObject()->getDocument();
		$drm->update();
		$drm->save();
		$this->redirect('drm_mouvements_generaux', $drm);
    }
    
    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
    	$drm = $this->getRoute()->getDRM();
        $certification_config = $this->getRoute()->getConfigCertification();

        $form = new DRMProduitAjoutForm($drm, $certification_config);
        if ($request->isMethod(sfWebRequest::POST)) {
    		$this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
                $form->addProduit();
                $drm->update();
				$drm->save();
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux', $drm))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form, 'certification_config' => $certification_config)))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form, 'certification_config' => $certification_config)));
    }
}
