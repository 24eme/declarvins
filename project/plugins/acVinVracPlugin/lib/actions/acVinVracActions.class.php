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
        $this->etablissement = EtablissementClient::getInstance()->find($request->getParameter('identifiant'));
    	if (!$this->etablissement && !$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
    		return $this->redirect('@acces_interdit');
    	}
        $this->forward404Unless($this->interpro = $this->getUser()->getCompte()->getGerantInterpro());
        $this->statut = $request->getParameter('statut');
        if (!$this->statut) {
            $this->statut = 0;
        }
        $this->forward404Unless(in_array($this->statut, array_merge(VracClient::getInstance()->getStatusContrat(true), array('TOUS'))));
		$this->pluriannuel = (int)$request->getParameter('pluriannuel', 0);
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
        $this->configurationProduit = ConfigurationProduitClient::getInstance()->find($this->interpro->configuration_produits);

        $this->vracs = array();
        $statuts = ($this->statut === 'TOUS')? VracClient::getInstance()->getStatusContrat(true) : [$this->statut];
        foreach ($statuts as $statut) {
                $this->vracs = array_merge($this->vracs, VracHistoryView::getInstance()->findForListingMode($this->etablissement, $this->interpro->get('_id'), $statut, $this->pluriannuel));
        }
        usort($this->vracs, array('VracClient', 'sortVracId'));

        $this->pluriannuels = VracHistoryView::getInstance()->findForListingMode($this->etablissement, $this->interpro->get('_id'), VracClient::STATUS_CONTRAT_SOLDE, 1);
        usort($this->pluriannuels, array('VracClient', 'sortVracId'));

        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    	    if ($request->isMethod(sfWebRequest::POST)) {
    	      	$this->form->bind($request->getParameter($this->form->getName()));
    	        if ($this->form->isValid()) {
    	            return $this->redirect("vrac_etablissement", ['identifiant' => $this->form->getEtablissement()->identifiant]);
    	        }
    	    }
        }
    }

	public function executeNouveau(sfWebRequest $request)
	{
		$this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
		$this->pluriannuel = (int)$request->getParameter('pluriannuel', 0);
		$vrac = $this->getNewVrac($this->etablissement, $this->pluriannuel);
		$this->redirect(array('sf_route' => 'vrac_etape',
                              'sf_subject' => $vrac,
                              'step' => $this->configurationVracEtapes->next($vrac->etape),
                              'etablissement' => $this->etablissement,
                              'pluriannuel' => $this->pluriannuel));
	}

	private function getNewVrac($etablissement, $pluriannuel = 0)
	{
		$vrac = new Vrac();
		$this->init($vrac, $etablissement);
		$vrac->interpro = $this->interpro->get('_id');
		$vrac->numero_contrat = uniqid();
		$vrac->add('referente', 1);
		$vrac->add('version', null);
		$vrac->contrat_pluriannuel = ($pluriannuel)? 1 : 0;
		return $vrac;
	}

	public function executeSupprimer(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();

        if (!$this->vrac->isNew())  {
	        if ($this->vrac->valide->date_validation) {
	        	$this->contratAnnulation($this->vrac, $this->vrac->getProduitInterpro(), $this->etablissement);
	        }
	        $this->vrac->delete();
        }

        if(!$this->etablissement) {
            $this->redirect('vrac_admin');
        }

		$this->redirect('vrac_etablissement', array('identifiant' => $this->etablissement->identifiant));
	}

	public function executeAnnexe(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $fname = null;
	    foreach ($this->vrac->_attachments as $filename => $fileinfos) {
    		$fname = $filename;
    	}
        $file = file_get_contents($this->vrac->getAttachmentUri($fname));
        if(!$file) {
            return $this->forward404($filename." n'existe pas pour ".$this->vrac->_id);
        }
        $this->getResponse()->setHttpHeader('Content-Type', 'application/pdf');
        $this->getResponse()->setHttpHeader('Content-disposition', sprintf('attachment; filename="%s"', $this->vrac->annexe_file));
        $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $this->getResponse()->setHttpHeader('Pragma', '');
        $this->getResponse()->setHttpHeader('Cache-Control', 'public');
        $this->getResponse()->setHttpHeader('Expires', '0');

        return $this->renderText($file);
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
        			$isEditable = $this->vrac->isEditable();
        			$lastStatut = $this->vrac->valide->statut;
        			$this->vrac->valide->statut = $this->statut;
        			if ($this->statut == VracClient::STATUS_CONTRAT_ANNULE) {
        				if ($lastStatut == VracClient::STATUS_CONTRAT_ATTENTE_VALIDATION)  {
        					$this->getUser()->setFlash('annulation', true);
        				} else {
	        				$this->vrac->annuler($this->getUser(), $this->etablissement);
	        				if ($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE) {
								$this->contratAnnulation($this->vrac, $this->vrac->getProduitInterpro(), $this->etablissement);
								$this->getUser()->setFlash('annulation', true);
	        				} else {
								$this->contratDemandeAnnulation($this->vrac, $this->vrac->getProduitInterpro(), $this->etablissement);
								$this->getUser()->setFlash('attente_annulation', true);
	        				}
        				}
        			}
        			if ($this->statut == VracClient::STATUS_CONTRAT_NONSOLDE && $lastStatut == VracClient::STATUS_CONTRAT_ATTENTE_ANNULATION) {
        					$this->vrac->remove('annulation');
							$this->contratRefusAnnulation($this->vrac, $this->vrac->getProduitInterpro(), $this->etablissement);
							$this->getUser()->setFlash('refus_annulation', true);
        			}
        			$this->vrac->save(false);
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
		$this->pluriannuel = (int)$request->getParameter('pluriannuel', 0);
        $this->etablissement = $this->getRoute()->getEtablissement();
        if (!$this->etablissement) {
        	if ($etablissement = EtablissementClient::getInstance()->find($request->getParameter('identifiant'))) {
        		$this->etablissement = $etablissement;
        	}
        }
        $this->vrac = $this->getRoute()->getVrac();
        if ($this->vrac->isNew()) {
        	$this->vrac = $this->getNewVrac($this->etablissement, $this->pluriannuel);
        }
		$this->init($this->vrac, $this->etablissement);
		$this->pluriannuel = $this->vrac->isPluriannuel();
        $this->referenceContratPluriannuel = $this->vrac->reference_contrat_pluriannuel;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->vrac->isValide() && !$this->vrac->hasVersion()) {
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
    	   $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
			if ($this->form->isValid()) {
				$this->vrac = $this->form->getUpdatedObject();
				if ($this->vrac->isNew()) {
					$this->vrac->numero_contrat = $this->getNumeroContrat();
				}
				$this->vrac->save();
				$sendEmail = (bool)$this->form->getValue('email');
				$brouillon = (bool)$this->form->getValue('brouillon');
				if ($brouillon) {
					return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => 'validation', 'etablissement' => $this->etablissement));
				}
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
                    	} elseif ($sendEmail && !$this->vrac->isRectificative()) {
                    		$this->contratValide($this->vrac, $sendEmail);
                    	} elseif ($sendEmail) {
                    		$this->saisieTerminee($this->vrac, $this->vrac->getProduitInterpro());
                    	}
                        $this->notifiePrixNonCoherent($this->vrac);
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
                    $this->notifiePrixNonCoherent($this->vrac);
					$this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
				}
				$this->redirect('vrac_validation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement, 'acteur' => $this->acteur));
			}
		}
	}

    private function notifiePrixNonCoherent($vrac) {
        if ($vrac->isVolumesAppellationsEnAlerte()) {
            Email::getInstance()->prixNonCoherent($vrac);
        }
    }

	public function executeAnnulation(sfWebRequest $request)
	{
		$this->forward404Unless($this->acteur = $request->getParameter('acteur', null));
		$acteurs = VracClient::getInstance()->getActeurs();
      	if (!in_array($this->acteur, $acteurs)) {
        	throw new sfException('Acteur '.$acteur.' invalide!');
      	}
		$this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->vrac, $this->etablissement);
        $annulationActeur = 'date_annulation_'.$this->acteur;
        $this->dateAnnulationActeur = $this->vrac->annulation->{$annulationActeur};
        $this->form = new VracannulationForm($this->vrac->annulation, $this->acteur, $this->getUser() , $this->etablissement);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$this->form->save();
				$this->getUser()->setFlash('annulation', true);
				if ($this->vrac->valide->statut == VracClient::STATUS_CONTRAT_ANNULE) {
					$this->contratAnnulation($this->vrac, $this->vrac->getProduitInterpro(), null);
					$this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
				}
				$this->redirect('vrac_annulation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement, 'acteur' => $this->acteur));
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
        if ($conflict = VracClient::getInstance()->find($vrac_rectificative->makeId())) {
        	$conflict->delete();
        }
        if ($this->etablissement) {
        	$vrac_rectificative->vous_etes = $this->vrac->getTypeByEtablissement($this->etablissement->identifiant);
        } else {
        	$vrac_rectificative->vous_etes = null;
        }
        $vrac_rectificative->save(false);

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
        if ($conflict = VracClient::getInstance()->find($vrac_modificative->makeId())) {
        	$conflict->delete();
        }
        if ($this->etablissement) {
        	$vrac_rectificative->vous_etes = $this->vrac->getTypeByEtablissement($this->etablissement->identifiant);
        } else {
        	$vrac_rectificative->vous_etes = null;
        }
        $vrac_modificative->save(false);

        return $this->redirect(array('sf_route' => 'vrac_etape',
                              'sf_subject' => $vrac_modificative,
                              'step' => $this->configurationVracEtapes->getFirst(),
                              'etablissement' => $this->etablissement));
    }

    public function executePluriannuel(sfWebRequest $request) {
        $this->forward404Unless($this->vrac = VracClient::getInstance()->find($request->getParameter('contrat')));
        $this->etablissement = EtablissementClient::getInstance()->find($request->getParameter('identifiant'));
        $this->init($this->vrac, $this->etablissement);
        $application = clone $this->vrac;
        $application->cleanPluriannuel();
        $application->reference_contrat_pluriannuel = $this->vrac->numero_contrat;
        $application->numero_contrat = VracClient::getInstance()->getNextNoContratApplication($this->vrac->numero_contrat);
        $application->constructId();
        $application->devalide();
        if ($this->etablissement) {
        	$application->vous_etes = $this->vrac->getTypeByEtablissement($this->etablissement->identifiant);
        } else {
        	$application->vous_etes = null;
        }
        $application->save(false);
        return $this->redirect(array('sf_route' => 'vrac_etape',
                              'sf_subject' => $application,
                              'step' => $this->configurationVracEtapes->next($this->configurationVracEtapes->getFirst()),
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

    public function executeApiContrats(sfWebRequest $request){

        $secret = sfConfig::get('app_api_contrats_secret');

        $cvi = $request->getParameter('cvi');
        $millesime = $request->getParameter('millesime');
        $epoch = $request->getParameter('epoch');

        if(abs(time() - $epoch) > 30) {
            http_response_code(403);
            die('Forbidden');
        }

        $md5 = $request->getParameter('md5');

        if ($md5 != md5($secret."/".$cvi."/".$millesime."/".$epoch)) {
            http_response_code(401);
            die("Unauthorized");
        }

        $contrats = VracClient::getInstance()->retrieveByCVIAndMillesime($cvi, $millesime, 'certifications/IGP/genres/TRANQ/appellations/MED/mentions/DEFAUT/lieux/DEFAUT/couleurs/rose');
        $result= array();
        foreach($contrats as $c){
			$result[$c->id]['numero'] = $c->value[VracSoussigneIdentifiantView::VRAC_VIEW_NUM];
            $result[$c->id]['acheteur'] = $c->value[VracSoussigneIdentifiantView::VRAC_VIEW_ACHETEUR_NOM];
			$result[$c->id]['volume'] = $c->value[VracSoussigneIdentifiantView::VRAC_VIEW_VOLPROP];
        }
        $this->getResponse()->setContentType('application/json');
        $data_json=json_encode($result);
        return $this->renderText($data_json);

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

	protected function contratAnnulation($vrac, $interpro, $etablissement = null) {
		return;
	}

	protected function contratDemandeAnnulation($vrac, $interpro, $etablissement = null) {
		return;
	}

	protected function contratRefusAnnulation($vrac, $interpro, $etablissement = null) {
		return;
	}




}