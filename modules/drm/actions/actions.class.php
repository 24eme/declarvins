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
      $this->etablissement = $this->getRoute()->getEtablissement();

  	  $historique = new DRMHistorique($this->etablissement->identifiant);
	    if ($historique->hasDRMInProcess()) {
	  	  
        throw new sfException('Une DRM est déjà en cours de saisie.');
	    }
	   
      $drm = DRMClient::getInstance()->createDoc($historique->etablissement, $request->getParameter('campagne'));
      
      $drm->mode_de_saisie =  DRM::MODE_DE_SAISIE_DTI;
      
      if($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN)) {
        $drm->mode_de_saisie = DRM::MODE_DE_SAISIE_PAPIER;
      }
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
      $etablissement = $this->getRoute()->getEtablissement();
      $drm = $this->getRoute()->getDRM();
      if ($drm->isValidee())
        throw new sfException('Vous ne pouvez pas supprimer une DRM validée');
      if ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN) && !$drm->isEnvoyee()) {
      	$drm->delete();
      } elseif (!$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN) && !$drm->isValidee()) {
      	$drm->delete();
      }
      $this->redirect('drm_mon_espace', $etablissement);
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      $this->etablissement = $this->getRoute()->getEtablissement();
      $this->historique = new DRMHistorique ($this->etablissement->identifiant);
      $this->formCampagne = new DRMCampagneForm($this->etablissement->identifiant);
      $this->hasDrmEnCours = $this->historique->hasDRMInProcess();
      if ($request->isMethod(sfWebRequest::POST)) {
	  	if ($this->hasDrmEnCours) {
	  		throw new sfException('Une DRM est déjà en cours de saisie.');
	  	}
    	$this->formCampagne->bind($request->getParameter($this->formCampagne->getName()));
  	  	if ($this->formCampagne->isValid()) {
  	  		$values = $this->formCampagne->getValues();
  	  		$drm = DRMClient::getInstance()->createDoc($this->etablissement->identifiant, $values['campagne']);
  	  		$drm->mode_de_saisie = DRM::MODE_DE_SAISIE_PAPIER;
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
    $this->etablissement = $this->getRoute()->getEtablissement();
    $this->campagne = $request->getParameter('campagne');
    $this->historique = new DRMHistorique ($this->etablissement->identifiant, $this->campagne);
  }

 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
    $this->drm = $this->getRoute()->getDRM();
    $this->etablissement = $this->getRoute()->getEtablissement();
    $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
    $this->form = new DRMInformationsForm(array(), array('is_admin' => $isAdmin));

    if ($request->isMethod(sfWebRequest::POST)) {
    	$this->form->bind($request->getParameter($this->form->getName()));

  	  if ($this->form->isValid()) {
	  		$values = $this->form->getValues();
                        if ($values['confirmation'] == "modification"){
                            $this->redirect('drm_modif_infos', $this->drm);
                        }elseif ($values['confirmation']) {
  				$this->drm->declarant->nom = $this->etablissement->nom;
  				$this->drm->declarant->raison_sociale = $this->etablissement->raison_sociale;
  				$this->drm->declarant->siret = $this->etablissement->siret;
  				$this->drm->declarant->cni = $this->etablissement->cni;
  				$this->drm->declarant->cvi = $this->etablissement->cvi;
  				$this->drm->declarant->siege->adresse = $this->etablissement->siege->adresse;
  				$this->drm->declarant->siege->code_postal = $this->etablissement->siege->code_postal;
  				$this->drm->declarant->siege->commune = $this->etablissement->siege->commune;
  				$this->drm->declarant->comptabilite->adresse = $this->etablissement->comptabilite->adresse;
  				$this->drm->declarant->comptabilite->code_postal = $this->etablissement->comptabilite->code_postal;
  				$this->drm->declarant->comptabilite->commune = $this->etablissement->comptabilite->commune;
  				$this->drm->declarant->no_accises = $this->etablissement->no_accises;
  				$this->drm->declarant->no_tva_intracommunautaire = $this->etablissement->no_tva_intracommunautaire;
  				$this->drm->declarant->service_douane = $this->etablissement->service_douane;		
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
    $this->etablissement = $this->getRoute()->getEtablissement();
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
	  
	  $historique = new DRMHistorique($this->etablissement->identifiant);
	  $next_drm = $historique->getNextByCampagne($this->drm->campagne);
	  if ($next_drm) {
	  	$next_drm = DRMClient::getInstance()->find($next_drm[DRMHistorique::VIEW_INDEX_ID]);
	  	if ($next_drm->precedente != $this->drm->_id) {
	  		$next_drm->precedente = $this->drm->_id;
	  		$next_drm->save();
	  	}
	  }
	  
	  if ($this->drm->needNextRectificative()) {
	    $drm_rectificative_suivante = $this->drm->generateRectificativeSuivante();
	    if ($drm_rectificative_suivante) {
	      $drm_rectificative_suivante->save();
	    }
	  }
	  $this->redirect('drm_visualisation', array('identifiant' => $this->etablissement->identifiant, 'campagne_rectificative' => $this->drm->getCampagneAndRectificative(), 'hide_rectificative' => 1));
    	}
    }
  }

  public function executeShowError(sfWebRequest $request) {
    $drm = $this->getRoute()->getDRM();
    $drmValidation = new DRMValidation($drm);
    $controle = $drmValidation->find($request->getParameter('type'), $request->getParameter('identifiant_controle'));
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
    $this->etablissement = $this->getRoute()->getEtablissement();
    $this->hide_rectificative = $request->getParameter('hide_rectificative');
    $this->drm_suivante = $this->drm->getSuivante();
  	$historique = new DRMHistorique($this->drm->identifiant);
    $this->hasDrmEnCours = $historique->hasDRMInProcess();
  }

  public function executeRectificative(sfWebRequest $request)
  {
    $this->etablissement = $this->getRoute()->getEtablissement();
  	$historique = new DRMHistorique($this->etablissement->identifiant);
	if ($historique->hasDRMInProcess()) {
		throw new sfException('Une DRM est déjà en cours de saisie.');
	}
    $drm = $this->getRoute()->getDRM();

    $drm_rectificative = $drm->generateRectificative();
    $drm_rectificative->save();

    return $this->redirect('drm_init', array('identifiant' => $this->etablissement->identifiant, 'campagne_rectificative' => $drm_rectificative->getCampagneAndRectificative()));
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
	public function executeDownloadNotice() {
        return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "docs/notice.pdf", "notice.pdf");
    }


    protected function renderPdf($path, $filename) {
        $this->getResponse()->setHttpHeader('Content-Type', 'application/pdf');
        $this->getResponse()->setHttpHeader('Content-disposition', 'attachment; filename="' . $filename . '"');
        $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $this->getResponse()->setHttpHeader('Content-Length', filesize($path));
        $this->getResponse()->setHttpHeader('Pragma', '');
        $this->getResponse()->setHttpHeader('Cache-Control', 'public');
        $this->getResponse()->setHttpHeader('Expires', '0');
        return $this->renderText(file_get_contents($path));
    }
  
}
