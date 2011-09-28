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
        $this->nbEtablissement = $request->getParameter('nb_etablissement', 1);
        $this->form = new ContratForm(new Contrat(), array('nbEtablissement' => $this->nbEtablissement));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $contrat = $this->form->save();
            }
        }
    }
   

}
