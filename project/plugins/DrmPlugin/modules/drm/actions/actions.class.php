<?php

/**
 * drm actions.
 *
 * @package    declarvin
 * @subpackage drm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class drmActions extends sfActions
{
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeInit(sfWebRequest $request) {
      $drm = $this->getUser()->getDRM();
      if ($drm->isNew()) {
          $drm->save();
      }
      $this->redirect('drm_informations');
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      $this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant);
      $this->nbDrmHistory = 3;
      $this->futurDrm = current($this->historique->getFutureDrm());
      $this->hasNewDrm = false;
      if (CurrentClient::getCurrent()->campagne >= ($this->futurDrm[1].'-'.$this->futurDrm[2]) && !$this->historique->hasDrmInProcess()) {
      	$this->hasNewDrm = true;
      	$this->nbDrmHistory = 2;
      }
  }
 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
      $this->tiers = $this->getUser()->getTiers();
  }

 /**
  * Executes historique action
  *
  * @param sfRequest $request A request object
  */
  public function executeHistorique(sfWebRequest $request)
  {
    $this->annee = $request->getParameter('annee');
	$this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant, $this->annee);
  }
 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executeValidation(sfWebRequest $request)
  {
      $this->drm = $this->getUser()->getDrm();
      $this->drmValidation = new DRMValidation($this->drm);
      if ($this->drmValidation->hasEngagements()) {
      	$this->form = new DRMValidationForm(array(), array('engagements' => $this->drmValidation->getEngagements()));
      	if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
			}
        }
      }
  }
  
}
