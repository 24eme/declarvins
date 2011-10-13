<?php

/**
 * validation actions.
 *
 * @package    declarweb
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
        $this->formLogin = new LoginForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
            if ($this->formLogin->isValid()) {
                $values = $this->formLogin->getValues();
                $this->getUser()->setAttribute('interpro_id', $values['interpro']);
                $this->getUser()->setAttribute('contrat_id', 'CONTRAT-' . $values['contrat']);
                $this->redirect('@validation_fiche');
            }
        }
    }

    public function init() {
        $this->interpro = $this->getUser()->getInterpro();
        $this->contrat = $this->getUser()->getContrat();
        $this->compte = $this->contrat->getCompteObject();
        $this->etablissements = $this->compte->getTiersCollection();
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
        $this->compte->save();
        $this->getUser()->setFlash('notification_general', 'Compte validé');
        $this->redirect('@validation_fiche');

        $this->setTemplate('fiche');
    }

    public function executeUploadCsv(sfWebRequest $request) {
        $this->forward404Unless($request->isMethod(sfWebRequest::POST));
        $this->init();
        
        $this->formUploadCsv = new UploadCSVForm();
        $this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
        if ($this->formUploadCsv->isValid()) {
            $file = $this->formUploadCsv->getValue('file');
            $contrat = $this->getUser()->getContrat();
            $contrat->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
            unlink($file->getSavedName());
            $this->getUser()->setFlash('notification_general', "Le fichier csv d'import a bien été uploader");
            $this->redirect('@validation_fiche');
        } 
        $this->setTemplate('fiche');
    }

    public function executeImport(sfWebRequest $request) {
    	$interpro = $this->getUser()->getInterpro();
    	$compte = $this->getUser()->getContrat()->getCompteObject();
        $import = new ImportEtablissementsCsv($interpro, $compte);
        $import->import();
        if (!$compte->interpro->exist($interpro->id)) {
            $compte->interpro->add($interpro->id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        } else {
            $compte->interpro->get($interpro->id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
        }
        $compte->save();
        $this->getUser()->setFlash('notification_general', "Les établissements ont bien été importés");
        $this->redirect('@validation_fiche');
    }

    public function executeArchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $this->forward404Unless($etablissement->compte == $this->getUser()->getContrat()->compte);
        $etablissement->statut = _Tiers::STATUT_ARCHIVER;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été archivé");
        $this->redirect('@validation_fiche');
    }
    
    public function executeDesarchiver(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $this->forward404Unless($etablissement->compte == $this->getUser()->getContrat()->compte);
        $this->forward404Unless($etablissement->statut == _Tiers::STATUT_ARCHIVER);
        $etablissement->statut = _Tiers::STATUT_ACTIF;
        $etablissement->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été désarchiver");
        $this->redirect('@validation_fiche');
    }

    public function executeDelier(sfWebRequest $request) {
        $this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
        $this->forward404Unless($etablissement->compte == $this->getUser()->getContrat()->compte);
        $etablissement->statut = _Tiers::STATUT_DELIER;
        $etablissement->save();
        $compte = $this->getUser()->getContrat()->getCompteObject();
        $compte->tiers->remove($etablissement->get('_id'));
        if ($compte->tiers->count() == 0) {
        	$compte->setStatut(_Compte::STATUT_INACTIVE);
        }
        $compte->save();
        $this->getUser()->setFlash('notification_general', "L'établissement a bien été délié");
        $this->redirect('@validation_fiche');
    }

}
