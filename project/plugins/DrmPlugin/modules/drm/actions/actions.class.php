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
      $drm = $this->getRoute()->getDRM();
      $this->redirect('drm_informations', $drm);
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      $this->historique = new DRMHistorique ($this->getUser()->getTiers()->identifiant);
  }

 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
	  $this->drm = $this->getRoute()->getDrm();
    $this->tiers = $this->getUser()->getTiers();
    $this->form = new DRMInformationsForm();

    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));

  	  if ($this->form->isValid()) {
	  		$values = $this->form->getValues();

	  		if ($values['confirmation']) {
  				$this->drm->declarant->nom = $this->tiers->nom;
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
        
			  $this->redirect('drm_mouvements_generaux', $this->drm);
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
    $this->drm = $this->getRoute()->getDrm();
    if ($this->drm->valide) {
	    $this->redirect('drm_succes', $this->drm);
    }
    $this->drmValidation = new DRMValidation($this->drm);
    $this->form = new DRMValidationForm(array(), array('engagements' => $this->drmValidation->getEngagements()));
    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));
	    if ($this->form->isValid()) {
  			$this->drm->valide = 1;
  			$this->drm->setDroits();
  			$this->drm->save();
  			$this->redirect('drm_succes', $this->drm);
    	}
    }
  }
 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executeSucces(sfWebRequest $request)
  {
    $this->drm = $this->getRoute()->getDrm();
  }

  public function executeRectificative(sfWebRequest $request)
  {
    $drm = $this->getRoute()->getDrm();

    $drm_rectificative = $drm->generateRectificative();
    $drm_rectificative->save();

    return $this->redirect(array('sf_route' => 'drm_historique',
                                 'annee' => $drm->getAnnee()));
  }


 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executePdf(sfWebRequest $request)
  {
    $this->forward404Unless($this->drm = $this->getUser()->getLastDrmValide());
  	$pdf = new ExportDRMPdf($this->drm);

    return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
}
