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
	  if ($this->compte->isVirtuel()) {
	  	return $this->redirect("admin");
	  }
	  
	  $nbEtablissement = 0;
	  $etablissements = array();
  	  foreach($this->compte->tiers as $tiers) {
        	$etablissement = EtablissementClient::getInstance()->find($tiers->id);
        	if ($etablissement->statut == Etablissement::STATUT_ACTIF) {
        		$etablissements[] = $etablissement;
            	$nbEtablissement++;
        	}
      }
      if ($nbEtablissement == 0) {
      		throw new sfException('Aucun établissement actif pour ce compte');
      }
      if ($nbEtablissement == 1) {
    		return $this->redirect("tiers_mon_espace", $etablissement);
      }

  	  $this->form = new TiersLoginForm($this->compte, true, $etablissements);
	
  	  if ($request->isMethod(sfWebRequest::POST)) {
    		$this->form->bind($request->getParameter($this->form->getName()));
    		$tiers = $this->form->process();
      		return $this->redirect("tiers_mon_espace", $tiers);
	  }
  }

  public function executeMonEspace(sfWebRequest $request) 
  {
    $this->etablissement = $this->getRoute()->getEtablissement();

  	if(($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR))) {

        return $this->redirect("drm_mon_espace", $this->etablissement);
    }

    if ($this->etablissement->hasDroit(EtablissementDroit::DROIT_VRAC)) {

        return $this->redirect("vrac_etablissement", $this->etablissement);
    }
  }
  
  public function executeProfil(sfWebRequest $request) 
  {
  	  $this->etablissement = $this->getRoute()->getEtablissement();
  	  $this->hasCompte = false;
  	  if ($this->compte_id = $this->etablissement->compte) {
  	    $this->hasCompte = true;
  	  	$this->compte = acCouchdbManager::getClient('_Compte')->find($this->compte_id);
  	  	$this->form = new CompteProfilForm($this->compte);
	      if ($request->isMethod(sfWebRequest::POST)) {
	      	$this->form->bind($request->getParameter($this->form->getName()));
	      	if ($this->form->isValid()) {
	      		$this->form->save();
           		$ldap = new Ldap();
           		$ldap->saveCompte($this->compte);
	      		$this->getUser()->setFlash('notice', 'Modifications effectuées avec succès');
	      		$this->redirect('profil', $this->etablissement);
	      	}
	      }
  	  }
  }
  
  public function executeStatut(sfWebRequest $request) 
  {
  	  $this->etablissement = $this->getRoute()->getEtablissement();
  	  if ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN)) {
  	  	if ($this->etablissement->statut == Etablissement::STATUT_ARCHIVE) {
  	  		$this->etablissement->statut = Etablissement::STATUT_ACTIF;
  	  	} else {
  	  		$this->etablissement->statut = Etablissement::STATUT_ARCHIVE;
  	  	}
  	  	$this->etablissement->save();
  	  	$this->getUser()->setFlash('notice', 'Modifications effectuées avec succès');
  	  }
  	  $this->redirect('profil', $this->etablissement);
  }
}
