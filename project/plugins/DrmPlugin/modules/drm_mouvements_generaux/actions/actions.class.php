<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$drm = $this->getUser()->getDrm();
		$this->forms = array();
		$this->certificationLibelle = array();
		foreach (ConfigurationClient::getCurrent()->declaration->labels as $certification => $item) {
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
    }
    
    public function executeSaveFormAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest() && $request->isMethod(sfWebRequest::POST)) {
			$form = new DRMMouvementsGenerauxProduitModificationForm($this->getRoute()->getObject());
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
               $form->save();
            }
        } 
        return sfView::NONE;
    }
    
    public function executeProductFormAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest()) {
        	$this->getResponse()->setContentType('text/json');
        	$drm = $this->getUser()->getDrm();
        	$certification = $request->getParameter('certification');
            $form = new DRMMouvementsGenerauxProduitAjoutForm($drm->produits->add($certification)->add(DRMMouvementsGenerauxProduitAjoutForm::NOEUD_TEMPORAIRE)->add());
            if ($request->isMethod(sfWebRequest::POST)) {
	            $form->bind($request->getParameter($form->getName()));
				if ($form->isValid()) {
					$form->save();
					$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
					return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('drm_mouvements_generaux'))));
				}
            }
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('produitLigneAjoutForm', array('form' => $form, 'certification' => $certification)))));
        } else {
            return sfView::NONE;
        }
    }
}
