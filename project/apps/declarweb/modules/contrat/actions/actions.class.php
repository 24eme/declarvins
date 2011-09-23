<?php

/**
 * contrat actions.
 *
 * @package    declarweb
 * @subpackage contrat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contratActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->formLogin = new LoginForm();
    
        
    if ($request->isMethod(sfWebRequest::POST)) {

        $this->formLogin->bind($request->getParameter($this->formLogin->getName()));
        if ($this->formLogin->isValid()) {
            $values = $this->formLogin->getValues();
            $this->getUser()->setAttribute('interpro_id', $values['interpro']);
            $this->getUser()->setAttribute('contrat_id', $values['contrat']);
            $this->redirect('@compte');
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeNouveauEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
    $this->form = new ContratEtablissementNouveauForm($this->contrat->etablissements->add());
    if ($request->isMethod(sfWebRequest::POST)) {

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
            $this->form->save();
            $this->redirect('@contrat_etablissement_nouveau');
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeModificationEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$this->forward404Unless($request->hasParameter('indice'));
  	$this->recapitulatif = $request->getParameter('recapitulatif');
  	$indice = $request->getParameter('indice');
  	$nextIndice = $indice + 1;
    $this->form = new ContratEtablissementModificationForm($this->contrat->etablissements->get($indice));
    if ($request->isMethod(sfWebRequest::POST)) {

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
            $this->form->save();
            if ($this->contrat->etablissements->exist($nextIndice)) {
            	if ($this->recapitulatif)
            		$this->redirect('@contrat_etablissement_recapitulatif');
            	else
            		$this->redirect('contrat_etablissement_modification', array('indice' => $nextIndice));
            } else 
            	$this->redirect('@contrat_etablissement_recapitulatif');
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeSuppressionEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$this->forward404Unless($request->hasParameter('indice'));
  	$this->recapitulatif = $request->getParameter('recapitulatif');
  	$indice = $request->getParameter('indice');
  	$nextIndice = $indice + 1;
  	$this->contrat->etablissements->remove($indice);
  	$this->contrat->save();
  	if ($this->contrat->etablissements->exist($nextIndice)) {
  		if ($this->recapitulatif)
  			$this->redirect('@contrat_etablissement_recapitulatif');
  		else
  			$this->redirect('contrat_etablissement_modification', array('indice' => $indice));
  	} else
  		$this->redirect('@contrat_etablissement_recapitulatif');
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeRecapitulatif(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeConfirmation(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  }
}
