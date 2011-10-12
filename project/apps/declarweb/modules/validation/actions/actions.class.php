<?php

/**
 * validation actions.
 *
 * @package    declarweb
 * @subpackage validation
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class validationActions extends sfActions
{
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
  	$this->formLogin = new LoginForm();
    
        
    if ($request->isMethod(sfWebRequest::POST)) {

        $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
        if ($this->formLogin->isValid()) {
            $values = $this->formLogin->getValues();
            $this->getUser()->setAttribute('interpro_id', $values['interpro']);
            $this->getUser()->setAttribute('contrat_id', 'CONTRAT-'.$values['contrat']);
            $this->redirect('@validation_fiche');
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeFiche(sfWebRequest $request)
  {
	$this->interpro = $this->getUser()->getInterpro();
	$this->contrat = $this->getUser()->getContrat();
	$this->compte = $this->contrat->getCompteObject();
	$this->etablissements = $this->compte->getTiersCollection();
	$this->form = new CompteModificationForm($this->compte);
	if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->form->getName())) {
		$this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$this->compte = $this->form->save();
			$this->getUser()->setFlash('maj', 'Les identifiants ont bien été mis à jour.');
			$this->redirect('@validation_fiche');
		}
	}
  }
  
  public function executeImport(sfWebRequest $request) {
      $import = new ImportEtablissementsCsv($this->getUser()->getInterpro(), $this->getUser()->getContrat()->getCompteObject());
      $import->import();
      $this->redirect('@validation_fiche');
  }
}
