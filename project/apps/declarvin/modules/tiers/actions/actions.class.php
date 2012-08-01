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
  	if ($this->getUser()->hasCredential(myUser::CREDENTIAL_TIERS)) {
  		
        return $this->redirect("@tiers_mon_espace");
  	} elseif ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN)) {
  		
        return $this->redirect("@admin");	
  	}

    $this->getUser()->signOutTiers();
	
	$this->compte = $this->getUser()->getCompte();
	$this->form = new TiersLoginForm($this->compte, true);
	if (count($this->compte->tiers) == 1) {
		$this->getUser()->signInTiers(acCouchdbManager::getClient()->retrieveDocumentById($this->compte->tiers->getFirst()->id));
		
        return $this->redirect("@tiers_mon_espace");
	}
  	if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
    	$tiers = $this->form->process();
    	$this->getUser()->signInTiers($tiers);
		
        return $this->redirect("@tiers_mon_espace");
	}
  }

  public function executeMonEspace(sfWebRequest $request) 
  {
  	if(($this->getUser()->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_DRM_DTI)) || ($this->getUser()->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_DRM_PAPIER) && $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN))) {

        return $this->redirect("drm_mon_espace", $this->getUser()->getEtablissement());
    }

    if ($this->getUser()->hasCredential(TiersSecurityUser::CREDENTIAL_DROIT_VRAC)) {

        return $this->redirect("@vrac");
    }
  }
}
