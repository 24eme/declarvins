<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$this->drm = $this->getRoute()->getDRM();
		$this->certifs = array();
		$this->certificationLibelle = array();
		$configuration = $this->getConfigurationProduit();
                $certification_config = $configuration->declaration->certifications;
		$certifications = ConfigurationClient::getCurrent()->getCertifications();
		$this->form = new DRMMouvementsGenerauxForm($configuration, $this->drm);
		foreach ($certifications as $c => $certification) {
			//if ($certification_config->exist($certification) && $certification_config->get($certification)->hasProduits($this->drm->getDepartement())) {
	            if (!isset($this->certifs[$c])) {
					$this->certifs[$c] = $certification;//$certification_config->get($certification)->libelle;
					$this->certificationLibelle[$c] = $certification;//$certification_config->get($certification)->libelle;
				}
			//}
		}
        $this->first_certification = null;
        if(count($this->drm->declaration->certifications) > 0) {
        	foreach ($this->drm->declaration->certifications as $key => $certification) {
        		if (count($this->drm->declaration->certifications->get($key)->genres) > 0) {
        			$this->first_certification = $this->drm->declaration->certifications->get($key);
        			break;
        		}
        	}
        }
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->form->bind($request->getParameter($this->form->getName()));
        	if ($this->form->isValid()) {
       			$this->form->save();
	            if($this->drm->declaration->hasMouvementCheck()) {
	        	   $this->drm->setCurrentEtapeRouting('recapitulatif');
	               return $this->redirect('drm_recap', $this->first_certification);
	            } else {
	               if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
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

        if ($this->drm->mode_de_saisie == DRMClient::MODE_DE_SAISIE_PAPIER || $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
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
        $config = ConfigurationClient::getCurrent();
        $certification = $this->getRoute()->getCertification();

        $form = new DRMProduitAjoutForm($drm, $config, $certification);
        if ($request->isMethod(sfWebRequest::POST)) {
    		$this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
                $form->addProduit();
				$drm->save();
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux', $drm))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form, 'certification' => $certification)))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form, 'certification' => $certification)));
    }
	
	protected function getConfigurationProduit()
	{
		$interpro = $this->getInterpro()->_id;
		return ConfigurationProduitClient::getInstance()->getOrCreate($interpro);
	}
	
	protected function getInterpro()
	{
		return $this->getUser()->getCompte()->getGerantInterpro();
	}
}
