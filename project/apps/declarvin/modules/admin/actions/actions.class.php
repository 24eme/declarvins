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
  	$this->getUser()->signOutCompte(myUser::NAMESPACE_COMPTE_PROXY);
    $this->getUser()->signOutCompte(myUser::NAMESPACE_COMPTE_TIERS);
    $this->form = new AdminCompteLoginForm();
    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
        	/*
        	 * @todo Utiliser signInCompte quand le cas sera en place
        	 */
        	//$this->getUser()->signInCompte($this->form->process());
        	$this->getUser()->signInFirst($this->form->process());
            $this->redirect('@tiers');
        }
    }
  }
}
