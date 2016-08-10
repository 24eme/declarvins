<?php

/**
 * validation actions.
 *
 * @package    declarvin
 * @subpackage validation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class validationActions extends sfActions {

    /**
     *
     *
     * @param sfRequest $request A request object
     */
    public function executeLogin(sfWebRequest $request) {
    	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
    	
        $this->formLogin = new LoginContratForm($this->interpro->get('_id'));
        $this->comptes_fictif = CompteAllView::getInstance()->findBy(1, 'CompteTiers', _Compte::STATUT_FICTIF)->rows;
        $this->comptes_attente = CompteAllView::getInstance()->findBy($this->interpro->get('_id'), 'CompteTiers', _Compte::STATUT_ATTENTE)->rows;
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
            if ($this->formLogin->isValid()) {
                $values = $this->formLogin->getValues();
                $this->redirect('validation_fiche', array('num_contrat' => $values['contrat']));
            }
        }
    }
    
    public function executeComptesCsv(sfWebRequest $request)
    {
    	ini_set('memory_limit', '1024M');
  		set_time_limit(0);
    	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
        $this->comptes = CompteAllView::getInstance()->findBy($this->interpro->get('_id'), 'CompteTiers')->rows;
        
        $csv_file = 'Compte Statut;Identifiant;Num Interne;Num Contrat;Interpro;Siret;Cni;Cvi;Num Accises;Num TVA intra;Email;Tel;Fax;Raison Sociale;Nom Com.;Adresse;Commune;CP;Pays;Famille;Sous famille;Adresse compta;Commune compta;CP compta;Pays compta;Douane;Complement;Statut;Compte nom;Compte prenom;Compte fonction;Compte email;Compte tel;Compte fax;Compte CIEL;Num carte pro;';
		$csv_file .= "\n";	
		foreach ($this->comptes as $c) {
			if($compte = _CompteClient::getInstance()->find($c->id)) {
				$dematerialise_ciel = 0;
				if ($compte->exist('dematerialise_ciel') && $compte->dematerialise_ciel) {
					$dematerialise_ciel = 1;
				}
				$compteInfosCsv = $compte->nom.';'.$compte->prenom.';'.$compte->fonction.';'.$compte->email.';'.$compte->telephone.';'.$compte->fax.';'.$dematerialise_ciel;
				$compteNonInfosCsv = ';;;;;;';
				if (count($compte->tiers) > 0) {
					foreach ($compte->tiers as $etablissementId => $etablissementInfos) {
						if ($etablissement = EtablissementClient::getInstance()->find($etablissementId)) {
						    $csv_file .= 
						    			$compte->statut.';'.
						    			$etablissement->identifiant.';'.
						    			$etablissement->num_interne.';'.
						    			str_replace('CONTRAT-', '', $etablissement->contrat_mandat).';'.
						    			$etablissement->interpro.';'.
						    			$etablissement->siret.';'.
						    			$etablissement->cni.';'.
						    			$etablissement->cvi.';'.
						    			$etablissement->no_accises.';'.
						    			$etablissement->no_tva_intracommunautaire.';'.
						    			$etablissement->email.';'.
						    			$etablissement->telephone.';'.
						    			$etablissement->fax.';'.
						    			$etablissement->raison_sociale.';'.
						    			$etablissement->nom.';'.
						    			$etablissement->siege->adresse.';'.
						    			$etablissement->siege->commune.';'.
						    			$etablissement->siege->code_postal.';'.
						    			$etablissement->siege->pays.';'.
						    			$etablissement->famille.';'.
						    			$etablissement->sous_famille.';'.
						    			$etablissement->comptabilite->adresse.';'.
						    			$etablissement->comptabilite->commune.';'.
						    			$etablissement->comptabilite->code_postal.';'.
						    			$etablissement->comptabilite->pays.';'.
						    			$etablissement->service_douane.';'.
										';'.
						    			$etablissement->statut.';'.
						    			$compteInfosCsv.';'.
						    			$etablissement->no_carte_professionnelle;
										$csv_file .= "\n";
						}
					}
				} else {
					if ($contrat = ContratClient::getInstance()->find($compte->contrat)) {
						foreach ($contrat->etablissements as $etablissement) {
							$csv_file .= 
						    			$compte->statut.';'.
						    			';'.
						    			';'.
						    			str_replace('CONTRAT-', '', $contrat->_id).';'.
						    			';'.
						    			$etablissement->siret.';'.
						    			$etablissement->cni.';'.
						    			$etablissement->cvi.';'.
						    			$etablissement->no_accises.';'.
						    			$etablissement->no_tva_intracommunautaire.';'.
						    			$etablissement->email.';'.
						    			$etablissement->telephone.';'.
						    			$etablissement->fax.';'.
						    			$etablissement->raison_sociale.';'.
						    			$etablissement->nom.';'.
						    			$etablissement->adresse.';'.
						    			$etablissement->commune.';'.
						    			$etablissement->code_postal.';'.
						    			$etablissement->pays.';'.
						    			$etablissement->famille.';'.
						    			$etablissement->sous_famille.';'.
						    			$etablissement->comptabilite_adresse.';'.
						    			$etablissement->comptabilite_commune.';'.
						    			$etablissement->comptabilite_code_postal.';'.
						    			$etablissement->comptabilite_pays.';'.
						    			$etablissement->service_douane.';'.
										';'.
						    			';'.
						    			$compteNonInfosCsv.';'.
						    			$etablissement->no_carte_professionnelle;
										$csv_file .= "\n";
						}
					}
				}
			}
		}	
	    $this->response->setContentType('text/csv');
	    $this->response->setHttpHeader('md5', md5($csv_file));
	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=comptes.csv");
	    return $this->renderText($csv_file);
    }
    
    public function executeSuppression(sfWebRequest $request)
    {
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->compte = $this->contrat->getCompteObject();
        $ldap = new Ldap();
  		$ldap->deleteCompte($this->compte);
  		$this->compte->delete();
  		$this->contrat->delete();
        $this->getUser()->setFlash('notice', 'Contrat d\'inscription supprimé avec succès');
        $this->redirect('validation_login');
    }
    
    public function init(sfWebRequest $request) {
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = EtablissementClient::getInstance()->getEtablissementsByContrat($this->contrat->_id);
        $this->formCompte = new CompteModificationForm($this->compte);
        $this->formLiaison = new LiaisonInterproForm($this->compte);
        $this->valide_interpro = false;
        if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
            $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
            if ($interpro->getStatut() != _Compte::STATUT_ATTENTE) {
                $this->valide_interpro = true;
            }
        }
        $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_INSCRIT);
    }

    /**
     *
     *
     * @param sfRequest $request A request object
     */
    public function executeFiche(sfWebRequest $request) {
    	$this->init($request);
    }

    public function executeCompte(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
    	$this->init($request);

        $this->formCompte->bind($request->getParameter($this->formCompte->getName()));
        if ($this->formCompte->isValid()) {
            $this->compte = $this->formCompte->save();  	
            $ldap = new Ldap();
  			$ldap->saveCompte($this->compte);
            $this->getUser()->setFlash('notice', 'Les identifiants ont bien été mis à jour.');
            $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
        }
        $this->setTemplate('fiche');
    }

    public function executeLiaison(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->init($request);
        
        $this->formLiaison->bind($request->getParameter($this->formLiaison->getName()));
        if ($this->formLiaison->isValid()) {
        	$this->formLiaison->save();
        	$valide = true;
        	foreach ($this->compte->interpro as $interpro) {
        		if ($interpro->statut != _Compte::STATUT_VALIDE) {
        			$valide = false;
        			break;
        		}
        	}
        	if ($this->compte->statut == _Compte::STATUT_FICTIF && $valide) {
        		$this->compte->setStatut(_Compte::STATUT_ATTENTE);
        		if (!$this->compte->login) {
        			$this->sendRegistration($this->compte);
        		}
        		$this->compte->save();
        		$ldap = new Ldap();
        		$ldap->saveCompte($this->compte);
        		$this->getUser()->setFlash('notice', 'Compte validé');
        	} else {
        		$this->getUser()->setFlash('notice', 'Liaisons interpro faites');
        	}
        	$this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
        }
        $this->setTemplate('fiche');
    }

    public function executeValidation(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->forward404Unless($interpro_id = $request->getParameter('interpro_id'));
        $this->init($request);

        if (!$this->compte->interpro->exist($interpro_id)) {
            $this->compte->interpro->add($interpro_id)->setStatut(_Compte::STATUT_VALIDE);
        } else {
            $this->compte->interpro->get($interpro_id)->setStatut(_Compte::STATUT_VALIDE);
        }
        $valide = true;
        foreach ($this->compte->interpro as $interpro) {
        	if ($interpro->statut != _Compte::STATUT_VALIDE) {
        		$valide = false;
        		break;
        	}
        }
        if ($valide) {
        	$this->compte->setStatut(_Compte::STATUT_ATTENTE);
        	if (!$this->compte->login) {
        		$this->sendRegistration($this->compte);
        	}
        }
        $this->compte->save();
		$ldap = new Ldap();
		$ldap->saveCompte($this->compte);
        $this->getUser()->setFlash('notice', 'Compte validé');
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
        $this->setTemplate('fiche');
    }
    
    private function sendRegistration($compte = null) 
    {
    	$this->forward404Unless($compte);
    	return Email::getInstance()->sendCompteRegistration($compte, $compte->email);
    }

    public function executeLier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = $request->getParameter("etablissement"));
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->forward404Unless($compte = $this->contrat->getCompteObject());
    	$interpro = $this->getUser()->getCompte()->getGerantInterpro();
    	$etablissement = EtablissementClient::getInstance()->find($etablissement);
        $etablissement->statut = Etablissement::STATUT_ACTIF;
        $etablissement->save();
		$compte->addEtablissement($etablissement);
		if (!$compte->interpro->exist($interpro->get('_id'))) {
        	$compte->interpro->add($interpro->get('_id'))->setStatut(_Compte::STATUT_ATTENTE);
		}
		$compte->save();
        $this->getUser()->setFlash('notice', "L'établissement a bien été lié");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }

    public function executeDelier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->find($request->getParameter("etablissement")));
    	$interpro = $this->getUser()->getCompte()->getGerantInterpro();
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $etablissement->compte = null;
        $etablissement->contrat_mandat = null;
        $etablissement->save();
        $compte = $this->contrat->getCompteObject();
        $compte->tiers->remove($etablissement->get('_id'));
        if ($compte->tiers->count() == 0) {
        	$compte->setStatut(_Compte::STATUT_ARCHIVE);
        }
        $compte->save();
        $this->getUser()->setFlash('notice', "L'établissement a bien été délié");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }
	  public function executePdf(sfWebRequest $request)
	  {
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
	  	$pdf = new ExportContratPdf($this->contrat);
		return $this->renderText($pdf->render($this->getResponse(), false));
	  }
	  public function executeConvention(sfWebRequest $request)
	  {
    	$this->forward404Unless($no_convention = $request->getParameter("num_convention"));
    	$this->convention = ConventionCielClient::getInstance()->retrieveById($no_convention);
    	
    	$path = sfConfig::get('sf_data_dir').'/convention-ciel';
    	
    	if (!file_exists($path.'/pdf/'.$this->convention->_id.'.pdf')) {
    		$fdf = tempnam(sys_get_temp_dir(), 'CONVENTIONCIEL');
    		file_put_contents($fdf, $this->convention->generateFdf());
    		exec("pdftk ".$path."/template.pdf fill_form $fdf output ".$path.'/pdf/'.$this->convention->_id.".pdf flatten");
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
  
  public function executeRedefinitionPassword(sfWebRequest $request)
  {
     $this->forward404Unless($compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     Email::getInstance()->sendRedefinitionMotDePasse($compte, $compte->email, array($compte->login));
     $this->getUser()->setFlash('notice', 'Demande de redéfinition du mot de passe envoyée');
     if ($compte->exist('contrat')) {
     	if ($contrat = ContratClient::getInstance()->find($compte->contrat)) {
     		$this->redirect('validation_fiche', array('num_contrat' => $contrat->no_contrat));
     	}
     }
     $this->redirect(array('sf_route' => 'compte_modification', 'login' => $compte->login));
  }
  
  public function executeRedefinitionInscription(sfWebRequest $request)
  {
  		$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
        $this->compte = $this->contrat->getCompteObject();
        Email::getInstance()->sendCompteRegistration($this->compte, $this->compte->email);
     	$this->getUser()->setFlash('notice', 'Demande du renvoi de l\'email d\'inscription envoyée');
     	$this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
  }

}
