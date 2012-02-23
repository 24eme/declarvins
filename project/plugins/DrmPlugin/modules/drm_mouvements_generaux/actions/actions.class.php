<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$drm = $this->getUser()->getDrm();
		$this->forms = array();
		$this->certificationLibelle = array();
		foreach (ConfigurationClient::getCurrent()->declaration->certifications as $certification => $item) {
			if (!isset($this->forms[$certification])) {
				$this->forms[$certification] = array();
				$this->certificationLibelle[$certification] = $item->libelle;
			}
			if ($drm->produits->exist($certification)) {
				foreach ($drm->produits->get($certification) as $appellation) {
					foreach ($appellation as $produit) {
						$this->forms[$certification][] = new DRMMouvementsGenerauxProduitModificationForm($produit);
					}
				}
			}
		}
        $this->first_certification = null;
        if(count($this->getUser()->getDrm()->declaration->certifications) > 0) {
            $this->first_certification = $this->getUser()->getDrm()->declaration->certifications->getFirst();
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
    	$drm = $this->getUser()->getDrm();
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
				return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux'))));
			} else {
				return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form, 'certification' => $certification)))));
			}
        }

        return $this->renderText($this->getPartial('popupAjout', array('form' => $form, 'certification' => $certification)));
    }
}
