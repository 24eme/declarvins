<?php

/**
 * admin actions.
 *
 * @package    declarvin
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  		//$this->getUser()->signOut();
  	 	//$this->getUser()->signIn('admin-inter-rhone');
        $this->formLogin = new LoginForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
            if ($this->formLogin->isValid()) {
                $values = $this->formLogin->getValues();
                $this->getUser()->setAttribute('interpro_id', $values['interpro']);
                $this->redirect('@produits');
            }
        }
  	
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementLogin(sfWebRequest $request)
  {
  		$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $this->form = new EtablissementSelectionForm($this->interpro->_id);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $etablissement = $values['etablissement'];
                $this->getUser()->signOutTiers();
                $this->getUser()->signInTiers(acCouchdbManager::getClient()->retrieveDocumentById($etablissement));
				return $this->redirect("@drm_mon_espace");
            }
        }
  	
  }
}
