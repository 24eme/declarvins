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
  public function executeNouvelle(sfWebRequest $request) {
      $drm = $this->getUser()->getDRM();
      $drm->save();
      $this->redirect('drm_informations', $drm);
  }
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeInit(sfWebRequest $request) {
      $drm = $this->getRoute()->getDRM();
      if ($etape = $drm->etape) {
      	$this->redirect($drm->getCurrentEtapeRouting(), $drm);
      } else {
		$drm->setCurrentEtapeRouting('ajouts_liquidations');
      	$this->redirect('drm_informations', $drm);
      }
  }
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeDelete(sfWebRequest $request) {
      $drm = $this->getRoute()->getDRM();
      if ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN) && !$drm->isEnvoyee()) {
      	$drm->delete();
      } elseif (!$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN) && !$drm->isValidee()) {
      	$drm->delete();
      }
      $this->redirect('drm/monEspace');
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      $this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant);
      $this->formCampagne = new DRMCampagne();
      if ($request->isMethod(sfWebRequest::POST)) {
    	$this->formCampagne->bind($request->getParameter($this->formCampagne->getName()));
  	  	if ($this->formCampagne->isValid()) {
  	  		$values = $this->formCampagne->getValues();
  	  		$drm = $this->getUser()->createDRMByCampagne($values['campagne']);
      		$drm->save();
      		$this->redirect('drm_informations', $drm);
  	  	}
      }
  }


 /**
  * Executes historique action
  *
  * @param sfRequest $request A request object
  */
  public function executeHistorique(sfWebRequest $request)
  {
    $this->campagne = $request->getParameter('campagne');
    $this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant, $this->campagne);
  }

 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
    $this->drm = $this->getRoute()->getDRM();
    $this->tiers = $this->getUser()->getTiers();
    $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
    $this->form = new DRMInformationsForm(array(), array('is_admin' => $isAdmin));

    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));

  	  if ($this->form->isValid()) {
	  		$values = $this->form->getValues();
                        if ($values['confirmation'] == "modification"){
                            $this->redirect('drm_modif_infos', $this->drm);
                        }elseif ($values['confirmation']) {
  				$this->drm->declarant->nom = $this->tiers->nom;
  				$this->drm->declarant->raison_sociale = $this->tiers->raison_sociale;
  				$this->drm->declarant->siret = $this->tiers->siret;
  				$this->drm->declarant->cni = $this->tiers->cni;
  				$this->drm->declarant->cvi = $this->tiers->cvi;
  				$this->drm->declarant->siege->adresse = $this->tiers->siege->adresse;
  				$this->drm->declarant->siege->code_postal = $this->tiers->siege->code_postal;
  				$this->drm->declarant->siege->commune = $this->tiers->siege->commune;
  				$this->drm->declarant->comptabilite->adresse = $this->tiers->comptabilite->adresse;
  				$this->drm->declarant->comptabilite->code_postal = $this->tiers->comptabilite->code_postal;
  				$this->drm->declarant->comptabilite->commune = $this->tiers->comptabilite->commune;
  				$this->drm->declarant->no_accises = $this->tiers->no_accises;
  				$this->drm->declarant->no_tva_intracommunautaire = $this->tiers->no_tva_intracommunautaire;
  				$this->drm->declarant->service_douane = $this->tiers->service_douane;		
  				$this->drm->save();
	  		}
			$this->drm->setCurrentEtapeRouting('ajouts_liquidations');		
        	$this->redirect('drm_mouvements_generaux', $this->drm);
    	}
    }
  }
  
  public function executeModificationInfos(sfWebRequest $request)
  {
      $this->drm = $this->getRoute()->getDRM();
  }

  public function executeDeclaratif(sfWebRequest $request)
  {
  	$this->drm = $this->getRoute()->getDRM();
	$this->drm->setCurrentEtapeRouting('declaratif');
  	$this->form = new DRMDeclaratifForm($this->drm);
  	$this->hasFrequencePaiement = ($this->drm->declaratif->paiement->douane->frequence)? true : false;
  	if($request->isMethod(sfWebRequest::POST)) {
  		$this->form->bind($request->getParameter($this->form->getName()));
  		if ($this->form->isValid()) {
  			$this->form->save();
			$this->drm->setCurrentEtapeRouting('validation');		
  			$this->redirect('drm_validation', $this->drm);
  		}
  	}
  }

  public function executePaiementFrequenceFormAjax(sfWebRequest $request)
  {
  	$this->forward404Unless($request->isXmlHttpRequest());
  	$drm = $this->getRoute()->getDRM();
  	return $this->renderText($this->getPartial('popupFrequence', array('drm' => $drm)));
  }

 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executeValidation(sfWebRequest $request)
  {
    $this->drm = $this->getRoute()->getDRM();
    $this->drmValidation = new DRMValidation($this->drm);
    $this->form = new DRMValidationForm(array(), array('engagements' => $this->drmValidation->getEngagements()));
    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
	if ($this->form->isValid()) {
		if ($this->drm->hasApurementPossible()) {
			$this->drm->apurement_possible = 1;
		}
	  $this->drm->validate();
	  $this->drm->save();
	  
	  if ($this->drm->needNextRectificative()) {
	    $drm_rectificative_suivante = $this->drm->generateRectificativeSuivante();
	    if ($drm_rectificative_suivante) {
	      $drm_rectificative_suivante->save();
	    }
	  }

	  $this->redirect('drm_visualisation', array('campagne_rectificative' => $this->drm->getCampagneAndRectificative(), 'hide_rectificative' => 1));
    	}
    }
  }

  public function executeShowError(sfWebRequest $request) {
    $drm = $this->getRoute()->getDRM();
    $drmValidation = new DRMValidation($drm);
    $controle = $drmValidation->find($request->getParameter('type'), $request->getParameter('identifiant'));
    $this->forward404Unless($controle);
    $this->getUser()->setFlash('control_message', $controle->getMessage());
    $this->getUser()->setFlash('control_css', "flash_".$controle->getType());
    $this->redirect($controle->getLien());
  }

 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executeVisualisation(sfWebRequest $request)
  {
    $this->drm = $this->getRoute()->getDRM();
    $this->hide_rectificative = $request->getParameter('hide_rectificative');
    $this->drm_suivante = $this->drm->getSuivante();
  }

  public function executeRectificative(sfWebRequest $request)
  {
    $drm = $this->getRoute()->getDRM();

    $drm_rectificative = $drm->generateRectificative();
    $drm_rectificative->save();

    return $this->redirect('drm_init', array('campagne_rectificative' => $drm_rectificative->getCampagneAndRectificative()));
  }


 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executePdf(sfWebRequest $request)
  {
  	
    ini_set('memory_limit', '512M');
    $this->drm = $this->getRoute()->getDRM();
  	$pdf = new ExportDRMPdf($this->drm);

    return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
}
