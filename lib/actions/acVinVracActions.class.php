<?php
class acVinVracActions extends sfActions
{
	public function preExecute()
  	{
  		if ($this->getRoute() instanceof EtablissementRoute) {
  			$etablissement = $this->getRoute()->getEtablissement();
  		} else {
  			$etablissement = null;
  		}
  		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement) {
  			$configuration = ConfigurationClient::getCurrent();
  			$this->forward404Unless($configuration->isApplicationOuverte($etablissement->interpro, 'vrac'));	
  		}
  		
  	}
	public function init($vrac, $etablissement)
	{
		$this->interpro = $this->getInterpro($vrac, $etablissement);
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
		$this->configurationVracEtapes = $this->configurationVrac->getEtapes();
	}
	
	public function executeIndex(sfWebRequest $request)
    {
    	if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		return $this->redirect('@acces_interdit');
    	}
        $this->etablissement = null;
        $this->forward404Unless($this->interpro = $this->getUser()->getCompte()->getGerantInterpro());
        $this->statut = $request->getParameter('statut');
        $this->statut = ($this->statut)? $this->statut : 0;
        $this->forward404Unless(in_array($this->statut, array_merge(VracClient::getInstance()->getStatusContrat(), array(0))));
        $this->vracs = array();
		$this->vracs_attente = array();
        $contrats = VracHistoryView::getInstance()->findLastByStatutAndInterpro($this->statut, $this->interpro->get('_id'));
        foreach ($contrats->rows as $contrat) {
        		$this->vracs[$contrat->id] = $contrat;
        }
        krsort($this->vracs);
        $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
	    if ($request->isMethod(sfWebRequest::POST)) {
	    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
	    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
	    	} else {
	      	$this->form->bind($request->getParameter($this->form->getName()));
	    	}
	      
	      if ($this->form->isValid()) {
	        return $this->redirect("vrac_etablissement", $this->form->getEtablissement());
	      }
	    }
    }

    public function executeEtablissement(sfWebRequest $request)
	{
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->statut = $request->getParameter('statut', VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION);
        if (!$this->statut) {
            $this->statut = VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION;
        }
        $this->forward404Unless(in_array($this->statut, VracClient::getInstance()->getStatusContrat()));
		$this->vracs = array();
		$this->vracs_attente = array();
        $contrats = array_reverse(VracSoussigneIdentifiantView::getInstance()->findByEtablissement($this->etablissement->identifiant)->rows);
        foreach ($contrats as $contrat) {
        	if (!$contrat->value[VracHistoryView::VRAC_VIEW_STATUT] || $contrat->value[VracHistoryView::VRAC_VIEW_STATUT] == VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION) {
        		$this->vracs_attente[$contrat->id] = $contrat;
        	} else {
        		$this->vracs[$contrat->id] = $contrat;
        	}
        }
        krsort($this->vracs);
        krsort($this->vracs_attente);
        $this->setTemplate('index');
	}

	public function executeNouveau(sfWebRequest $request)
	{
		$this->vrac = $this->getRoute()->getVrac();
                $this->etablissement = $this->getRoute()->getEtablissement();
		$vrac = $this->getNewVrac($etablissement);
		$this->redirect(array('sf_route' => 'vrac_etape', 
                              'sf_subject' => $vrac, 
                              'step' => $this->configurationVracEtapes->next($vrac->etape), 
                              'etablissement' => $this->etablissement));
	}
	
	private function getNewVrac($etablissement)
	{
		$vrac = new Vrac();
		$this->init($vrac, $etablissement);
		$vrac->interpro = $this->interpro->get('_id');
		$vrac->numero_contrat = uniqid();
		$vrac->add('referente', 1);
		$vrac->add('version', null);
		return $vrac;
	}

	public function executeSupprimer(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        
        if (!$this->vrac->isNew())  {
	        if ($this->vrac->valide->date_saisie) {
	        	$this->contratAnnulation($this->vrac, $this->etablissement);
	        }
	        $this->vrac->delete();
        }

        if(!$this->etablissement) {
            $this->redirect('vrac_admin');
        }

		$this->redirect('vrac_etablissement', array('sf_subject' => $this->etablissement));
	}

	public function executeStatut(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        if ($this->statut = $request->getParameter('statut')) {
        	$statuts = VracClient::getInstance()->getStatusContrat();
        	if (in_array($this->statut, $statuts)) {
        		$statut_credentials = VracClient::getInstance()->getStatusContratCredentials();
        		$statut_credentials = $statut_credentials[$this->vrac->valide->statut];
        		if (in_array($this->statut, $statut_credentials)) {
        			$valide = $this->vrac->isValide();
        			$this->vrac->valide->statut = $this->statut;
        			$this->vrac->save();
        			if ($this->statut == VracClient::STATUS_CONTRAT_ANNULE) {
						$this->contratAnnulation($this->vrac, $this->etablissement);
						$this->getUser()->setFlash('annulation', true);
        			}
        			if (!$valide) {
        				$this->vrac->delete();
        				if(!$this->etablissement) {
				            $this->redirect('vrac_admin');
				        }
				
						$this->redirect('vrac_etablissement', array('sf_subject' => $this->etablissement));
        			}
        			$this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
        		}
        	} else {
        		throw new sfError404Exception('Unknown status');
        	}
        } else {
        	throw new sfError404Exception('Status needed');
        }
        $this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
	}

	public function executeModification(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->getUser()->setAttribute('vrac_modification', $this->vrac->_id);
        return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => 'soussigne', 'etablissement' => $this->etablissement));
	}

    public function executeEdition(sfWebRequest $request) {
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);
        $this->etape = $this->configurationVracEtapes->getFirst();
        if($this->vrac->etape) {
            $this->etape = $this->vrac->etape;
        }

         return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => $this->etape, 'etablissement' => $this->etablissement));
    }

	public function executeEtape(sfWebRequest $request)
	{
		$this->forward404Unless($this->etape = $request->getParameter('step'));
		if (!$this->getUser()->getCompte()) {
			throw new sfException('Compte required');
		}
        $this->etablissement = $this->getRoute()->getEtablissement();
        if (!$this->etablissement) {
        	if ($etablissement = EtablissementClient::getInstance()->find($request->getParameter('identifiant'))) {
        		$this->etablissement = $etablissement;
        	}
        }
        $this->vrac = $this->getRoute()->getVrac();
        if ($this->vrac->isNew()) {
        	$this->vrac = $this->getNewVrac($this->etablissement);
        } 
		$this->init($this->vrac, $this->etablissement);
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->vrac->isValide()) {
            return $this->redirect('vrac_valide_admin');
        }

        if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && !$this->vrac->isEnCoursSaisie()) {
            return $this->redirect('vrac_valide', array('identifiant' => $this->etablissement->identifiant));
        }
		if ($this->vrac->etape && $this->configurationVracEtapes->hasSupForNav($this->etape, $this->vrac->etape)) {
			$this->vrac->setEtape($this->etape);
		}
		if (!$this->vrac->etape) {
			$this->vrac->setEtape($this->etape);
		}
		$this->form = $this->getForm($this->interpro->_id, $this->etape, $this->configurationVrac, $this->etablissement, $this->getUser(), $this->vrac);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$this->vrac = $this->form->getUpdatedObject();
				if ($this->vrac->isNew()) {
					$this->vrac->numero_contrat = $this->getNumeroContrat();
				}
				$this->vrac->save();
				$sendEmail = (bool)$this->form->getValue('email');
				if (!$this->configurationVracEtapes->next($this->etape)) {
					$interpro = $this->getInterpro($this->vrac, $this->etablissement);
					$this->vrac->interpro = $interpro->get('_id');
					$this->vrac->save();
                    $this->getUser()->setFlash('termine', true);
                    if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
                    	if ($this->getUser()->getAttribute('vrac_modification', null)) {
                    		if ($sendEmail) {
                    			$this->contratModifie($this->vrac, $sendEmail);
                    		}
                    		$this->getUser()->setAttribute('vrac_modification', null);
                    	} elseif ($sendEmail) {
                    		$this->contratValide($this->vrac, $sendEmail);
                    	}
                    	return $this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
                    } else {
                    	$this->saisieTerminee($this->vrac, $this->interpro);
                    	return $this->redirect('vrac_validation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement, 'acteur' => $this->vrac->vous_etes));
                    }
			        
				}

				if (!$this->vrac->has_transaction && $this->configurationVracEtapes->next($this->etape) == 'transaction') {
					
                    return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => $this->configurationVracEtapes->next('transaction'), 'etablissement' => $this->etablissement));
				}
				
                return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => $this->configurationVracEtapes->next($this->etape), 'etablissement' => $this->etablissement));
			}
		}
	}


  public function executeSetEtablissementInformations(sfWebRequest $request)
  {
		if (!$this->getUser()->getCompte()) {
			throw new sfException('Compte required');
		}
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();   
        $this->soussigne = $request->getParameter('soussigne', null);
        $this->type = $request->getParameter('type', null);
        $this->etape = $request->getParameter('step', null);

        if (!$this->soussigne) {

        	throw new sfException('Numéro d\'établissement du soussigne requis');
        }

        if (!$this->type) {

        	throw new sfException('Type requis');
        }

        if (!$this->etape) {

        	throw new sfException('Etape requis');
        }

        $this->init($this->vrac, $this->etablissement);
        $this->soussigne = EtablissementClient::getInstance()->find($this->soussigne);
        if (!$this->vrac->exist($this->type)) {

            throw new sfException('Type '.$this->type.' n\'existe pas');
        }
  	
        $this->vrac->storeSoussigneInformations($this->type, $this->soussigne);
		$this->form = $this->getForm($this->interpro->_id, $this->etape, $this->configurationVrac, $this->etablissement, $this->getUser(), $this->vrac);
		
        if ($this->type != 'mandataire') {
			return $this->renderPartial('form_etablissement', array('form' => $this->form[$this->type]));
		}
		
    	return $this->renderPartial('form_mandataire', array('form' => $this->form[$this->type]));	
  }
  
  public function executePdf(sfWebRequest $request)
  {
    	ini_set('memory_limit', '512M');
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->interpro = $this->getInterpro($this->vrac, $this->etablissement);
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
  		$pdf = new ExportVracPdf($this->vrac, $this->configurationVrac);
    	return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
  public function executePdfTransaction(sfWebRequest $request)
  {
    	ini_set('memory_limit', '512M');
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
    	$this->interpro = $this->getInterpro($this->vrac, $this->etablissement);
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
  		$pdf = new ExportVracPdfTransaction($this->vrac, $this->configurationVrac, true);
    	return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
	public function executeVisualisation(sfWebRequest $request)
	{
		$this->vrac = $this->getRoute()->getVrac();
        /*if (!$this->vrac->isValide()) {
            throw new sfException("Le contrat vrac n°".$this->vrac->numero_contrat." n'est pas validé");
        }*/
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);
	}
	
	public function executeValidation(sfWebRequest $request)
	{
		$this->forward404Unless($this->acteur = $request->getParameter('acteur', null));
		$acteurs = VracClient::getInstance()->getActeurs();
      	if (!in_array($this->acteur, $acteurs)) {
        	throw new sfException('Acteur '.$acteur.' invalide!');
      	}
		$this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);
        $validationActeur = 'date_validation_'.$this->acteur;
        $this->dateValidationActeur = $this->vrac->valide->{$validationActeur};
        if ($this->vrac->isValide()) {
        	if ($this->etablissement)
        		$this->redirect('vrac_valide', array('identifiant' => $this->etablissement->identifiant));
        	else 
        		$this->redirect('vrac_valide_admin');
        }
        $this->form = new VracSignatureForm($this->vrac->valide, $this->acteur);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$this->form->save();
				$this->contratValidation($this->vrac, $this->acteur);
				$this->getUser()->setFlash('valide', true);
				if ($this->vrac->isValide()) {
					$this->contratValide($this->vrac);
					$this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
				}
				$this->redirect('vrac_validation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement, 'acteur' => $this->acteur));
			}
		}
	}
	
	public function executeValide(sfWebRequest $request) {
		$this->etablissement = $this->getRoute()->getEtablissement();
    }
	
	public function executeValideAdmin(sfWebRequest $request) {
		$this->etablissement = null;
		$this->setTemplate('valide');
    }
    


    public function executeRectificative(sfWebRequest $request) {    
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);

        $vrac_rectificative = $this->vrac->generateRectificative();
        $vrac_rectificative->save();

        return $this->redirect(array('sf_route' => 'vrac_etape', 
                              'sf_subject' => $vrac_rectificative, 
                              'step' => $this->configurationVracEtapes->getFirst(), 
                              'etablissement' => $this->etablissement));
    }

    public function executeModificative(sfWebRequest $request) {
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);

        $vrac_modificative = $this->vrac->generateModificative();
        $vrac_modificative->save();

        return $this->redirect(array('sf_route' => 'vrac_etape', 
                              'sf_subject' => $vrac_modificative, 
                              'step' => $this->configurationVracEtapes->getFirst(), 
                              'etablissement' => $this->etablissement));
    }

	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $user, $vrac)
	{
		return VracFormFactory::create($etape, $configurationVrac, $etablissement, $user, $vrac);
	}
	
	public function getNumeroContrat()
	{
		return VracClient::getInstance()->getNextNoContrat();
	}
	
	public function getInterpro($vrac, $etablissement = null)
	{
		if ($interpro = $vrac->getProduitInterpro()) {
			return $interpro;
		}
        if($etablissement) {
            return $etablissement->getInterproObject();
        }
        return $this->getUser()->getCompte()->getGerantInterpro();
	}
	

    public function executeModificationRestreinte(sfWebRequest $request) {
        if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            
            return $this->redirect('@acces_interdit');
        }

        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);

        if(!$this->vrac->isModifiableVolume()) {
            throw new sfException("Le volume n'est pas modifiable sur ce contrat");
        }

        $this->form = new VracModificationForm($this->configurationVrac, $this->etablissement, $this->getUser(), $this->vrac);

        if(!$request->isMethod(sfRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

        if(!$this->form->isValid()) {
            
            return sfView::SUCCESS;
        }

        $this->form->save();

        return $this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
    }

	public function getConfigurationVrac($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro_id);
	}
	
	protected function saisieTerminee($vrac, $interpro) {
		return;
	}
	
	protected function contratValide($vrac) {
		return;
	}
	
	protected function contratModifie($vrac) {
		return;
	}
	
	protected function contratValidation($vrac, $acteur) {
		return;
	}
	
	protected function contratAnnulation($vrac, $etablissement = null) {
		return;
	}


}