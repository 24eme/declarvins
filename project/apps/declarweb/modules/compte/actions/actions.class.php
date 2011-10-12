<?php

/**
 * compte actions.
 *
 * @package    declarweb
 * @subpackage compte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class compteActions extends sfActions {
    /**
     * 
     *
     * @param sfRequest $request A request object
     */
		
    public function executeNouveau(sfWebRequest $request) {
   		$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
        $this->form = new CompteTiersAjoutForm(new CompteTiers(), array('contrat' => $this->contrat));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compteTiers = $this->form->save();
                $this->contrat->setCompte($compteTiers->get('_id'));
                $this->contrat->save();
                $this->redirect('@contrat_etablissement_recapitulatif');
            }
        }
    }
   

}
