<?php

/**
 * tiers actions.
 *
 * @package    declarvin
 * @subpackage tiers
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class tiersActions extends sfActions
{
 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin(sfWebRequest $request) 
  {
	$this->getUser()->signOutTiers();
	$this->compte = $this->getUser()->getCompte();
	$this->form = new TiersLoginForm($this->compte, true);
	if (count($this->compte->tiers) == 1) {
		$this->getUser()->signInTiers(sfCouchdbManager::getClient('_Tiers')->retrieveDocumentById($this->compte->tiers[0]->id));
		return $this->redirect("@drm_mon_espace");
	}
  	if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
    	$tiers = $this->form->process();
    	$this->getUser()->signInTiers($tiers);
		return $this->redirect("@drm_mon_espace");
	}
  }
}
