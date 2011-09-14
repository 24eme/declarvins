<?php

/**
 * compte actions.
 *
 * @package    declarweb
 * @subpackage compte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class compteActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->compte = $this->getUser()->getCompte();
  	$this->form = new CompteModificationForm($this->compte);

	if ($request->isMethod(sfWebRequest::POST)) {
		$this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$this->compte = $this->form->save();
			$this->getUser()->setFlash('maj', 'Les identifiants ont bien été mis à jour.');
			$this->redirect('@compte');
		}
	}
  }
}
