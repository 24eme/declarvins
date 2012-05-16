<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$this->drm = $this->getRoute()->getDrm();
        $this->form = new DRMMouvementsGenerauxProduitsForm($this->drm);
		$this->forms = array();
		$this->certificationLibelle = array();
		foreach ($this->drm->getDeclaration()->certifications as $certification => $item) {
			if (!isset($this->forms[$certification])) {
				$this->forms[$certification] = array();
				$this->certificationLibelle[$certification] = $item->libelle;
			}
			if ($this->drm->declaration->certifications->exist($certification)) {
                $details = $this->drm->declaration->certifications->get($certification)->getProduits();
				foreach ($details as $detail) {
					$this->forms[$certification][] = new DRMMouvementsGenerauxProduitForm($detail);
				}
			}
		}
        $this->first_certification = null;
        if(count($this->drm->declaration->certifications) > 0) {
            $this->first_certification = $this->drm->declaration->certifications->getFirst();
        }
        if ($request->isMethod(sfWebRequest::POST)) {

            if($this->drm->declaration->hasMouvementCheck()) {
        	   $this->drm->setCurrentEtapeRouting('recapitulatif');
        	   $this->redirect('drm_recap', $this->first_certification);
            } else {
               $this->drm->setCurrentEtapeRouting('declaratif');
               $this->redirect('drm_declaratif', $this->drm);
            }

        }
    }
    
    public function executeUpdateAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $drm = $this->getRoute()->getDrm();
        $detail = $this->getRoute()->getObject()->getDetail();
        $this->forward404Unless($detail->hasPasDeMouvement());
		$form = new DRMMouvementsGenerauxProduitForm($this->getRoute()->getObject());
    	$form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
       		$form->save();
       		return $this->renderText(json_encode(array("success" => true)));
        }
        
        return $this->renderText(json_encode(array("success" => false)));
    }

    public function executeUpdateProduitsAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $drm = $this->getRoute()->getDrm();
        $this->forward404Unless($drm->declaration->hasPasDeMouvement());
        $form = new DRMMouvementsGenerauxProduitsForm($drm);
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            foreach($drm->produits as $certification_produit) {
                foreach($certification_produit as $appellation_produit) {
                    foreach($appellation_produit as $produit) {
                        $produit->pas_de_mouvement = $form->getValue('pas_de_mouvement');
                    }
                }
            }
            $drm->save();
            return $this->renderText(json_encode(array("success" => true)));
        } 
        return $this->renderText(json_encode(array("success" => false)));
    }

    public function executeStockEpuise(sfWebRequest $request) 
    {
    	$drm = $this->getRoute()->getDrm();
    	$this->forward404Unless($drm->declaration->hasPasDeMouvement());
    	$this->forward404Unless($drm->declaration->hasStockEpuise());

    	foreach($drm->produits as $certification_produit) {
    		foreach($certification_produit as $appellation_produit) {
    			foreach($appellation_produit as $produit) {
    				$produit->pas_de_mouvement = 1;
    			}
    		}
    	}
        $drm->setCurrentEtapeRouting('declaratif');
        $this->redirect('drm_declaratif', $drm);
    }
    
    public function executeDeleteAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
		$this->getRoute()->getObject()->delete();
		$this->getRoute()->getObject()->getDocument()->save();
        return sfView::NONE;
    }
    
    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
    	$drm = $this->getRoute()->getDrm();
    	$this->forward404Unless($certification_config = ConfigurationClient::getCurrent()->declaration->certifications->get($request->getParameter('certification')));

        $form = new DRMProduitAjoutForm($drm, $certification_config);
        if ($request->isMethod(sfWebRequest::POST)) {
    		$this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
                $form->addProduit();
				$drm->save();
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux', $drm))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form, 'certification' => $certification_config->getKey())))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form, 'certification' => $certification_config->getKey())));
    }
}
