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
        $this->formLogin = new LoginContratForm();
        $this->comptes_fictif = CompteMandatsView::getInstance()->findByStatut(_Compte::STATUT_FICTIF);
        $this->comptes_attente = CompteMandatsView::getInstance()->findByStatut(_Compte::STATUT_ATTENTE);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
            if ($this->formLogin->isValid()) {
                $values = $this->formLogin->getValues();
                $this->redirect('validation_fiche', array('num_contrat' => $values['contrat']));
            }
        }
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
		return $this->renderText($pdf->render($this->getResponse()));
	  }
  
  public function executeRedefinitionPassword(sfWebRequest $request)
  {
     $this->forward404Unless($compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     Email::getInstance()->sendRedefinitionMotDePasse($compte, $compte->email);
     $this->getUser()->setFlash('notice', 'Demande de redéfinition du mot de passe envoyée');
     if ($compte->exist('contrat')) {
     	if ($contrat = ContratClient::getInstance()->find($compte->contrat)) {
     		$this->redirect('validation_fiche', array('num_contrat' => $contrat->no_contrat));
     	}
     }
     $this->redirect(array('sf_route' => 'compte_modification', 'login' => $compte->login));
  }

}
