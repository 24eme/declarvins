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
	  if (!$this->getUser()->hasCredential('plateforme')) {
	  	return $this->redirect('tiers_forbidden');
	  }
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
      		return $this->redirect('tiers_forbidden');
      }
      if ($nbEtablissement == 1) {
    		return $this->redirect("tiers_mon_espace", current($etablissements));
      }

  	  $this->form = new TiersLoginForm($this->compte, true, $etablissements);
	
  	  if ($request->isMethod(sfWebRequest::POST)) {
    		$this->form->bind($request->getParameter($this->form->getName()));
    		$tiers = $this->form->process();
      		return $this->redirect("tiers_mon_espace", $tiers);
	  }
  }
  
  public function executeConnexion(sfWebRequest $request)
  {
  	if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
  		$this->getUser()->signOut();
  		$this->getUser()->signIn($request->getParameter('login'));
  		return $this->redirect('@tiers');
  	}
  	return $this->redirect('tiers_forbidden');
  }
  
  public function executeAccessForbidden(sfWebRequest $request)
  {
  	
  }

  public function executeMonEspace(sfWebRequest $request) 
  {
    $this->etablissement = $this->getRoute()->getEtablissement();
    $configuration = ConfigurationClient::getCurrent();

    if ($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI) && $configuration->isApplicationOuverte($this->etablissement->interpro, 'drm', $this->etablissement)) {
		$this->configureAlerteDrm($this->etablissement);
		
		if ($this->getUser()->getCompte()->isTiers() && !$this->getUser()->getCompte()->dematerialise_ciel) {
			$convention = $this->getUser()->getCompte()->getConventionCiel();
			if (!$convention) {
				return $this->redirect("convention_ciel", $this->etablissement);
			}
			if (!$convention->valide) {
				return $this->redirect("convention_ciel", $this->etablissement);
			}
		}
		
        return $this->redirect("drm_mon_espace", $this->etablissement);
    }

    if ($this->etablissement->hasDroit(EtablissementDroit::DROIT_VRAC) && $configuration->isApplicationOuverte($this->etablissement->interpro, 'vrac')) {

        return $this->redirect("vrac_etablissement", $this->etablissement);
    }
    
  	return $this->redirect("profil", $this->etablissement); // solution temporaire

  	if(($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) || ($this->etablissement->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER) && $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR))) {
    	$this->configureAlerteDrm($this->etablissement);
        return $this->redirect("drm_mon_espace", $this->etablissement);
    }
  }
  
  private function configureAlerteDrm($etablissement)
  {
  	if ($etablissement) {
    	$historique = new DRMHistorique($etablissement->identifiant);
    	$infos = array();
    	if ($drm = $historique->getLastDRM()) {
    		$infos['periode'] = $drm->periode;
    		$infos['valide'] = (int)$drm->isValidee();
    	}
    	$this->getUser()->setAttribute('last_drm', $infos);
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
  	  if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
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
  
  public function executeAdhesionCiel(sfWebRequest $request)
  {
  	$this->etablissement = $this->getRoute()->getEtablissement();
  }
  
  public function executeAcceptationCiel(sfWebRequest $request)
  {
  	$this->compte = $this->getUser()->getCompte();
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	if ($this->compte instanceof acVinCompteTiers) {
	  	$this->compte->dematerialise_ciel = 1;
	  	$this->compte->save();
	  	$this->getUser()->setFlash('notice', 'Adhésion au service CIEL effectuée avec succès');
  	}
  	return $this->redirect("drm_mon_espace", $this->etablissement);
  }

  public function executePdf(sfWebRequest $request)
  {
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->etablissement->getCompteObject();
  	$this->contrat = ContratClient::getInstance()->find($this->compte->contrat);
  	$pdf = new ExportContratPdf($this->contrat);
  	return $this->renderText($pdf->render($this->getResponse(), false));
  }
  
  public function executeConvention(sfWebRequest $request)
  {
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->etablissement->getCompteObject();
  	$this->convention = $this->compte->getConventionCiel();
  	 
  	$path = sfConfig::get('sf_data_dir').'/convention-ciel';
  	 
  	if (!file_exists($path.'/pdf/'.$this->convention->_id.'.pdf')) {
  		$fdf = tempnam(sys_get_temp_dir(), 'CONVENTIONCIEL');
  		file_put_contents($fdf, $this->convention->generateFdf());
  		exec("pdftk ".$path."/template.pdf fill_form $fdf output  /dev/stdout flatten |  gs -o ".$path.'/pdf/'.$this->convention->_id.".pdf -sDEVICE=pdfwrite -dEmbedAllFonts=true  -sFONTPATH=\"/usr/share/fonts/truetype/freefont\" - ");
  		unlink($fdf);
  	}
  	 
  	$response = $this->getResponse();
  	$response->setHttpHeader('Content-Type', 'application/pdf');
  	$response->setHttpHeader('Content-disposition', 'attachment; filename="' . basename($path.'/pdf/'.$this->convention->_id.'.pdf') . '"');
  	$response->setHttpHeader('Content-Length', filesize($path.'/pdf/'.$this->convention->_id.'.pdf'));
  	$response->setHttpHeader('Pragma', '');
  	$response->setHttpHeader('Cache-Control', 'public');
  	$response->setHttpHeader('Expires', '0');
  
  	return $this->renderText(file_get_contents($path.'/pdf/'.$this->convention->_id.'.pdf'));
  }

  public function executeAvenant(sfWebRequest $request)
  {
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->etablissement->getCompteObject();
  	$this->contrat = ContratClient::getInstance()->find($this->compte->contrat);
  	$pdf = new ExportAvenantPdf($this->contrat);
  	return $this->renderText($pdf->render($this->getResponse(), false));
  }
  
}
