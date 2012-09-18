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

	  $this->compte = $this->getUser()->getCompte();

    if (count($this->compte->tiers) == 1) {
      
      return $this->redirect("tiers_mon_espace", EtablissementClient::getInstance()->find($this->compte->tiers->getFirst()->id));
    }

  	$this->form = new TiersLoginForm($this->compte, true);
	
  	if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
    	$tiers = $this->form->process();
		
      return $this->redirect("tiers_mon_espace", $tiers);
	  }
  }

  public function executeMonEspace(sfWebRequest $request) 
  {
    $this->etablissement = $this->getRoute()->getEtablissement();

  	if(($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN))) {

        return $this->redirect("drm_mon_espace", $this->etablissement);
    }

    if ($this->etablissement->hasDroit(EtablissementDroit::DROIT_VRAC)) {

        return $this->redirect("vrac_etablissement", $this->etablissement);
    }
  }
  
  public function executeProfil(sfWebRequest $request) 
  {
  	  $this->form = new CompteProfilForm($this->getUser()->getCompte());
      if ($request->isMethod(sfWebRequest::POST)) {
      	$this->form->bind($request->getParameter($this->form->getName()));
      	if ($this->form->isValid()) {
      		$this->form->save();
      		$this->getUser()->setFlash('notice', 'Modifications effectuées avec succès');
      		$this->redirect('@profil');
      	}
      }
  }
}
