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
	if (count($this->compte->tiers) == 1) {
		$this->getUser()->signInTiers(sfCouchdbManager::getClient('_Tiers')->retrieveDocumentById($this->compte->tiers[0]->_id));
		return $this->redirect("@mon_espace");
	}
	if ($tiers_id = $request->getParameter('tiers_id')) {
		$this->getUser()->signInTiers(sfCouchdbManager::getClient('_Tiers')->retrieveDocumentById($tiers_id));
		return $this->redirect("@mon_espace");
	}
  }
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request) 
  {
	
  }
}
