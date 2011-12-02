<?php

class drm_mouvements_generauxActions extends sfActions
{
	public function executeIndex(sfWebRequest $request) 
	{
		$this->nbProduit = $request->getParameter('nb_produit', 1);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
            }
        }
    }
    
    public function executeAddTableRowItemAjax(sfWebRequest $request) 
    {
        if ($request->isXmlHttpRequest() && $request->isMethod(sfWebRequest::POST)) {
        	$numeroProduit = $request->getParameter('nb_produit');
            $formTmp = DRMMouvementsGenerauxProduitAjoutForm::getNewItem($numeroProduit);
            return $this->renderPartial('produitLigneForm', array('numeroProduit' => $numeroProduit, 'form_item' => $formTmp[DRMMouvementsGenerauxProduitAjoutForm::EMBED_FORM_NAME][$numeroProduit]));
        } else {
            $this->forward404();
        }
    }
}
