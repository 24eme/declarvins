<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$drm = $this->getUser()->getDrm();
		$this->aopForms = ($drm->produits->exist('AOP'))? new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('AOP')) : null;
		$this->igpForms = ($drm->produits->exist('IGP'))? new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('IGP')) : null;
		$this->vinssansigForms = ($drm->produits->exist('VINSSANSIG'))? new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('VINSSANSIG')) : null;
    }
    
    public function executeAddTableRowItemAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest()) {
        	$drm = $this->getUser()->getDrm();
            return $this->renderPartial('produitLigneAjoutForm', array('label' => $request->getParameter('label'), 'form' => new DRMMouvementsGenerauxProduitAjoutForm($drm->produits->add($request->getParameter('label'))->add())));
        } else {
            $this->forward404();
        }
    }
    
    public function executeSaveTableRowItemAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest() && $request->isMethod(sfWebRequest::POST)) {
        	$drm = $this->getUser()->getDrm();
            $form = new DRMMouvementsGenerauxProduitAjoutForm($drm->produits->add($request->getParameter('label'))->add());
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
               $form->save();
               return $this->renderPartial('produitLigneModificationForm', array('form' => $form, 'object' => $form->getObject())); 
            } else {
            	return $this->renderPartial('produitLigneAjoutForm', array('label' => $request->getParameter('label'), 'form' => $form)); 
            }
        } else {
            $this->forward404();
        }
    }
    
    public function executeSaveFormAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest() && $request->isMethod(sfWebRequest::POST)) {
        	$drm = $this->getUser()->getDrm();
        	if ($request->getParameter('label') == 'AOP') {
        		$form = new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('AOP'));
        	} elseif ($request->getParameter('label') == 'IGP') {
        		$form = new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('IGP'));
        	} else {
        		$form = new DRMMouvementsGenerauxProduitCollectionForm($drm->produits->get('VINSSANSIG'));
        	}
        	$form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
               $form->save();
            }
            return sfView::NONE;
        	
        } else {
            $this->forward404('non');
        }
    }
}
