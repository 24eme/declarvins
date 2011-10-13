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
	
	public function init()
	{
		$this->interpro = $this->getUser()->getInterpro();
		$this->contrat = $this->getUser()->getContrat();
		$this->compte = $this->contrat->getCompteObject();
		$this->etablissements = $this->compte->getTiersCollection();
		$this->form = new CompteModificationForm($this->compte);
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
	public function executeFiche(sfWebRequest $request)
	{
		$this->init();
	}
	
	public function executeCompte(sfWebRequest $request)
	{
		$this->init();
		if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->form->getName())) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$this->compte = $this->form->save();
				$this->getUser()->setFlash('maj', 'Les identifiants ont bien été mis à jour.');
				$this->redirect('@validation_fiche');
			}
		}
		$this->setTemplate('fiche');
	}
	
	public function executeLiaison(sfWebRequest $request)
	{
		$this->init();
		if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->formLiaison->getName())) {
			$this->formLiaison->bind($request->getParameter($this->formLiaison->getName()));
			if ($this->formLiaison->isValid()) {
				$this->formLiaison->save();
				$this->getUser()->setFlash('general', 'Liaisons interpro faites');
				$this->redirect('@validation_fiche');
			}
		}
		$this->setTemplate('fiche');
	}
	
	public function executeValidation(sfWebRequest $request)
	{
		$this->init();
		if ($request->isMethod(sfWebRequest::POST) && $request->getParameter('interpro_id')) {
			$interpro_id = $request->getParameter('interpro_id');
			if (!$this->compte->interpro->exist($interpro_id)) {
				$this->compte->interpro->add($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
			} else {
				$this->compte->interpro->get($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
			}
			$this->compte->save();
			$this->getUser()->setFlash('general', 'Compte validé');
			$this->redirect('@validation_fiche');
		}
		$this->setTemplate('fiche');
	}


	public function executeUploadCsv(sfWebRequest $request) {
		$this->forward404Unless($request->isMethod('post'));
		$this->form = new UploadCSVForm();
                
		$this->form->bind($request->getParameter('csv'), $request->getFiles('csv'));
		if ($this->form->isValid()) {
                    $file = $this->form->getValue('file');                 
                    $contrat = $this->getUser()->getContrat();
                    $contrat->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
                    unlink($file->getSavedName());
		} else {
                    throw new sfException("Csv not valid");
                }
		$this->redirect('@validation_fiche');
	}

	public function executeImport(sfWebRequest $request) {
		$import = new ImportEtablissementsCsv($this->getUser()->getInterpro(), $this->getUser()->getContrat()->getCompteObject());
		$import->import();
		$this->redirect('@validation_fiche');
	}

	public function executeArchiver(sfWebRequest $request) {
		$this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
		$this->forward404Unless($etablissement->compte == $this->getUser()->getContrat()->compte);
		$etablissement->statut = _Tiers::STATUT_ARCHIVER;
		$etablissement->save();

		$this->redirect('@validation_fiche');
	}

	public function executeDelier(sfWebRequest $request) {
		$this->forward404Unless($etablissement = EtablissementClient::getInstance()->retrieveById($request->getParameter("etablissement")));
		$this->forward404Unless($etablissement->compte == $this->getUser()->getContrat()->compte);
		$etablissement->statut = _Tiers::STATUT_DELIER;
		$etablissement->save();
		$compte = $this->getUser()->getContrat()->getCompteObject();
		$compte->tiers->remove($etablissement->get('_id'));
		$compte->save();
		$this->redirect('@validation_fiche');
	}


}
