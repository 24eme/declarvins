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
    	$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $this->formLogin = new LoginContratForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
            if ($this->formLogin->isValid()) {
                $values = $this->formLogin->getValues();
                $this->redirect('validation_fiche', array('num_contrat' => $values['contrat']));
            }
        }
    }

    /**
     *
     *
     * @param sfRequest $request A request object
     */
    public function executeFiche(sfWebRequest $request) {
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        
        $this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $import = new ImportEtablissementsCsv($this->interpro);
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = $this->compte->getTiersCollection();
        $this->etablissementsCsv = array_diff_key($import->getEtablissementsByContrat($this->contrat), $this->compte->tiers->toArray());

        $this->formCompte = new CompteModificationForm($this->compte);
        $this->formUploadCsv = new UploadCSVForm();
        $this->formLiaison = new LiaisonInterproForm($this->compte);
        $this->valide_interpro = false;
        if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
            $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
            if ($interpro->getStatut() != _Compte::STATUT_VALIDATION_ATTENTE) {
                $this->valide_interpro = true;
            }
        }
        $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_ACTIVE);
    }

    public function executeCompte(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        
        $this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $import = new ImportEtablissementsCsv($this->interpro);
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = $this->compte->getTiersCollection();
        $this->etablissementsCsv = array_diff_key($import->getEtablissementsByContrat($this->contrat), $this->compte->tiers->toArray());

        $this->formCompte = new CompteModificationForm($this->compte);
        $this->formUploadCsv = new UploadCSVForm();
        $this->formLiaison = new LiaisonInterproForm($this->compte);
        $this->valide_interpro = false;
        if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
            $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
            if ($interpro->getStatut() != _Compte::STATUT_VALIDATION_ATTENTE) {
                $this->valide_interpro = true;
            }
        }
        $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_ACTIVE);

        $this->formCompte->bind($request->getParameter($this->formCompte->getName()));
        if ($this->formCompte->isValid()) {
            $this->compte = $this->formCompte->save();
            $this->getUser()->setFlash('notification_compte', 'Les identifiants ont bien été mis à jour.');
            $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
        }

        $this->setTemplate('fiche');
    }

    public function executeLiaison(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        
        $this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $import = new ImportEtablissementsCsv($this->interpro);
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = $this->compte->getTiersCollection();
        $this->etablissementsCsv = array_diff_key($import->getEtablissementsByContrat($this->contrat), $this->compte->tiers->toArray());

        $this->formCompte = new CompteModificationForm($this->compte);
        $this->formUploadCsv = new UploadCSVForm();
        $this->formLiaison = new LiaisonInterproForm($this->compte);
        $this->valide_interpro = false;
        if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
            $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
            if ($interpro->getStatut() != _Compte::STATUT_VALIDATION_ATTENTE) {
                $this->valide_interpro = true;
            }
        }
        $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_ACTIVE);
        if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->formLiaison->getName())) {
            $this->formLiaison->bind($request->getParameter($this->formLiaison->getName()));
            if ($this->formLiaison->isValid()) {
                $this->formLiaison->save();
                $this->getUser()->setFlash('notification_general', 'Liaisons interpro faites');
            	$this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
            }
        }
        $this->setTemplate('fiche');
    }

    public function executeValidation(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->forward404Unless($interpro_id = $request->getParameter('interpro_id'));
        
        $this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $import = new ImportEtablissementsCsv($this->interpro);
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = $this->compte->getTiersCollection();
        $this->etablissementsCsv = array_diff_key($import->getEtablissementsByContrat($this->contrat), $this->compte->tiers->toArray());

        $this->formCompte = new CompteModificationForm($this->compte);
        $this->formUploadCsv = new UploadCSVForm();
        $this->formLiaison = new LiaisonInterproForm($this->compte);
        $this->valide_interpro = false;
        if ($this->compte->interpro->exist($this->interpro->get('_id'))) {
            $interpro = $this->compte->interpro->get($this->interpro->get('_id'));
            if ($interpro->getStatut() != _Compte::STATUT_VALIDATION_ATTENTE) {
                $this->valide_interpro = true;
            }
        }
        $this->compte_active = ($this->compte->getStatut() == _Compte::STATUT_ACTIVE);

        if (!$this->compte->interpro->exist($interpro_id)) {
            $this->compte->interpro->add($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        } else {
            $this->compte->interpro->get($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        }
        $valide = true;
        foreach ($this->compte->interpro as $interpro) {
        	if ($interpro->statut != _Compte::STATUT_VALIDATION_VALIDE) {
        		$valide = false;
        		break;
        	}
        }
        if ($valide) {
        	$this->compte->setStatut(_Compte::STATUT_ACTIVE);
        	if (!$this->compte->login) {
        		//$this->sendRegistration($this->compte);
        	}
        } else {
        	$this->compte->setStatut(_Compte::STATUT_INACTIVE);
        }
        $this->compte->save();

		$ldap = new Ldap();
		$ldap->saveCompte($this->compte);

        $this->getUser()->setFlash('notification_general', 'Compte validé');
        
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));

        $this->setTemplate('fiche');
    }
    
    private function sendRegistration($compte = null) {
    	$this->forward404Unless($compte);
    	$numeroContrat = explode('-', $compte->contrat);
    	$numeroContrat = $numeroContrat[1];
    	
    	$mess = 'Bonjour '.$compte->nom.' '.$compte->prenom.',
    	
Votre contrat numéro '.$numeroContrat.' à été validé.

Vous devez créer votre compte en suivant le lien suivant : <a href="'.$this->getController()->genUrl(array('sf_route' => 'compte_nouveau', 'nocontrat' => $numeroContrat), false).'">Création de mon compte</a>

Cordialement,

DéclarVins';
        $message = Swift_Message::newInstance()
                ->setFrom(array(sfConfig::get('app_inscription_from_email') => sfConfig::get('app_inscription_from_name')))
                ->setTo($this->getUser()->getCompte()->email)
                ->setSubject(sfConfig::get('app_inscription_subject'))
                ->setBody($mess);        
        $this->getMailer()->send($message);
        
    }

    public function executeArchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $etablissement->statut = Etablissement::STATUT_ARCHIVER;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été archivé");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }
    
    public function executeDesarchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $this->forward404Unless($etablissement->statut == Etablissement::STATUT_ARCHIVER);
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $etablissement->statut = Etablissement::STATUT_ACTIF;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été désarchiver");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }

    public function executeLier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = $request->getParameter("etablissement"));
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $this->forward404Unless($compte = $this->contrat->getCompteObject());
    	$this->forward404Unless($interpro = $this->getUser()->getInterpro());
    	$import = new ImportEtablissementsCsv($interpro);
    	$etablissement = $import->getEtablissementByIdentifiant($etablissement);
        $etablissement->statut = Etablissement::STATUT_ACTIF;
        $etablissement->save();
		$compte->addEtablissement($etablissement);
        $compte->interpro->add($interpro->get('_id'))->setStatut(_Compte::STATUT_VALIDATION_ATTENTE);
        $compte->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été lié");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }

    public function executeDelier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
    	$this->forward404Unless($interpro = $this->getUser()->getInterpro());
    	$this->forward404Unless($no_contrat = $request->getParameter("num_contrat"));
    	$this->contrat = ContratClient::getInstance()->retrieveById($no_contrat);
        $etablissement->delete();
        $compte = $this->contrat->getCompteObject();
        $compte->tiers->remove($etablissement->get('_id'));
        if ($compte->tiers->count() == 0) {
        	if ($compte->interpro->exist($interpro->get('_id'))) {
        		$compte->interpro->remove($interpro->get('_id'));
        	}
        	$compte->setStatut(_Compte::STATUT_INACTIVE);
        }
        $compte->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été délié");
        $this->redirect('validation_fiche', array('num_contrat' => $this->contrat->no_contrat));
    }

}
