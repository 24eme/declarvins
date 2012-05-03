<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$this->drm = $this->getRoute()->getDrm();
		$this->forms = array();
		$this->certificationLibelle = array();
		foreach (ConfigurationClient::getCurrent()->declaration->certifications as $certification => $item) {
			if (!isset($this->forms[$certification])) {
				$this->forms[$certification] = array();
				$this->certificationLibelle[$certification] = $item->libelle;
			}
			if ($this->drm->produits->exist($certification)) {
				foreach ($this->drm->produits->get($certification) as $appellation) {
					foreach ($appellation as $produit) {
						$this->forms[$certification][] = new DRMMouvementsGenerauxProduitModificationForm($produit);
					}
				}
			}
		}
        $this->first_certification = null;
        if(count($this->drm->declaration->certifications) > 0) {
            $this->first_certification = $this->drm->declaration->certifications->getFirst();
        }
        if ($request->isMethod(sfWebRequest::POST)) {

            if($this->drm->produits->hasMouvement()) {
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
        if ($request->isMethod(sfWebRequest::POST)) {
        	$this->getResponse()->setContentType('text/json');
			$form = new DRMMouvementsGenerauxProduitModificationForm($this->getRoute()->getObject());
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
            	$drm = $this->getUser()->getDrm();
            	$detail = $this->getRoute()->getObject()->getDetail();
            	if ($detail->hasPasDeMouvement()) {
               		$form->save();
               		return $this->renderText(json_encode(array("success" => true)));
            	} else {
            		return $this->renderText(json_encode(array("success" => false, "notice" => "Attention, il existe du mouvement pour ce produit")));
            	}
            }
        } 
        return $this->renderText(json_encode(array("success" => false)));
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
    	$certification = $request->getParameter('certification');

        $form = new DRMProduitAjoutForm(
            $drm->produits->add($certification)->add(DRM::NOEUD_TEMPORAIRE)->add(),
            $this->getUser()->getTiers()->interpro
            );
        if ($request->isMethod(sfWebRequest::POST)) {
    		$this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
			if ($form->isValid()) {
				$form->save();
				$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux', $drm))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form, 'certification' => $certification)))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form, 'certification' => $certification)));
    }
}
