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
        	$this->drm->setCurrentEtapeRouting('recapitulatif');
        	$this->redirect('drm_recap', $this->first_certification);
        }
    }
    
    public function executeUpdateAjax(sfWebRequest $request) 
    {
        $this->forward404Unless($request->isXmlHttpRequest());
        if ($request->isMethod(sfWebRequest::POST)) {
			$form = new DRMMouvementsGenerauxProduitModificationForm($this->getRoute()->getObject());
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
               $form->save();
            }
        } 
        return sfView::NONE;
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
