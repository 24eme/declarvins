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
                $this->getUser()->setAttribute('contrat_id', 'CONTRAT-' . $values['contrat']);
                $this->redirect('@validation_fiche');
            }
        }
    }

    public function init() {
        $this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
        $this->forward404Unless($this->contrat = $this->getUser()->getContrat());
         $this->forward404Unless($this->compte = $this->contrat->getCompteObject());
        $import = new ImportEtablissementsCsv($this->interpro);
        
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

    /**
     *
     *
     * @param sfRequest $request A request object
     */
    public function executeFiche(sfWebRequest $request) {
        $this->init();
    }

    public function executeCompte(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->init();

        $this->formCompte->bind($request->getParameter($this->formCompte->getName()));
        if ($this->formCompte->isValid()) {
            $this->compte = $this->formCompte->save();
            $this->getUser()->setFlash('notification_compte', 'Les identifiants ont bien été mis à jour.');
            $this->redirect('@validation_fiche');
        }

        $this->setTemplate('fiche');
    }

    public function executeLiaison(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->init();
        if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->formLiaison->getName())) {
            $this->formLiaison->bind($request->getParameter($this->formLiaison->getName()));
            if ($this->formLiaison->isValid()) {
                $this->formLiaison->save();
                $this->getUser()->setFlash('notification_general', 'Liaisons interpro faites');
                $this->redirect('@validation_fiche');
            }
        }
        $this->setTemplate('fiche');
    }

    public function executeValidation(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->forward404Unless($interpro_id = $request->getParameter('interpro_id'));
        $this->init();

        if (!$this->compte->interpro->exist($interpro_id)) {
            $this->compte->interpro->add($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        } else {
            $this->compte->interpro->get($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        }
        $this->compte->setStatut(_Compte::STATUT_ACTIVE);
        $this->compte->save();

		$ldap = new Ldap();
		$ldap->saveCompte($this->compte);

        $this->getUser()->setFlash('notification_general', 'Compte validé');
        $this->redirect('@validation_fiche');

        $this->setTemplate('fiche');
    }

    public function executeArchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $etablissement->statut = _Tiers::STATUT_ARCHIVER;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été archivé");
        $this->redirect('@validation_fiche');
    }
    
    public function executeDesarchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $this->forward404Unless($etablissement->statut == _Tiers::STATUT_ARCHIVER);
        $etablissement->statut = _Tiers::STATUT_ACTIF;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été désarchiver");
        $this->redirect('@validation_fiche');
    }

    public function executeLier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = $request->getParameter("etablissement"));
        $this->forward404Unless($compte = $this->getUser()->getContrat()->getCompteObject());
    	$this->forward404Unless($interpro = $this->getUser()->getInterpro());
    	$import = new ImportEtablissementsCsv($interpro);
    	$etablissement = $import->getEtablissementByIdentifiant($etablissement);
        $etablissement->statut = _Tiers::STATUT_ACTIF;
        $etablissement->save();
		$compte->addEtablissement($etablissement);
        $compte->interpro->add($interpro->get('_id'))->setStatut(_Compte::STATUT_VALIDATION_ATTENTE);
        $compte->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été lié");
        $this->redirect('@validation_fiche');
    }

    public function executeDelier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
    	$this->forward404Unless($interpro = $this->getUser()->getInterpro());
        $etablissement->delete();
        $compte = $this->getUser()->getContrat()->getCompteObject();
        $compte->tiers->remove($etablissement->get('_id'));
        if ($compte->tiers->count() == 0) {
        	if ($compte->interpro->exist($interpro->get('_id'))) {
        		$compte->interpro->remove($interpro->get('_id'));
        	}
        	$compte->setStatut(_Compte::STATUT_INACTIVE);
        }
        $compte->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été délié");
        $this->redirect('@validation_fiche');
    }

}
