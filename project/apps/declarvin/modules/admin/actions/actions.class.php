<?php

/**
 * admin actions.
 *
 * @package    declarvin
 * @subpackage admin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class adminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  		$this->getUser()->setAttribute('interpro_id', $this->getUser()->getCompte()->getInterpro());
  		$this->redirect('@etablissement_login');
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementLogin(sfWebRequest $request)
  {
  	$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}
      
      if ($this->form->isValid()) {
        
        	return $this->redirect("tiers_mon_espace", $this->form->getEtablissement());
      }
    }
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementProfilLogin(sfWebRequest $request)
  {
  	$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}
      
      if ($this->form->isValid()) {
        
        	return $this->redirect("profil", $this->form->getEtablissement());
      }
    }
  }

 /**
  * Executes libelles action
  *
  * @param sfRequest $request A request object
  */
  public function executeLibelles(sfWebRequest $request)
  {
	$this->messages = MessagesClient::getInstance()->findAll(); 
    $this->droits = ConfigurationClient::getCurrent()->droits;
    $this->labels = ConfigurationClient::getCurrent()->labels;
    $this->controles = ControlesClient::getInstance()->findAll();
    $this->configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getUser()->getCompte()->getInterpro());
  }
  public function executeLibelleModification(sfWebRequest $request)
  {
  	$this->forward404Unless($this->key = $request->getParameter('key'));
  	$this->forward404Unless($this->type = $request->getParameter('type'));
  	if ($this->type == 'messages') {
  		$object = MessagesClient::getInstance()->retrieveMessages();
  	} elseif ($this->type == 'droits') {
  		$object = ConfigurationClient::getCurrent()->droits;
  	} elseif ($this->type == 'labels') {
  		$object = ConfigurationClient::getCurrent()->labels;
  	} elseif ($this->type == 'controles') {
  		$object = ControlesClient::getInstance()->retrieveControles();
  	} elseif ($this->type == 'vrac') {
  		$object = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getUser()->getCompte()->getInterpro());
  	} else {
  		throw new sfException('type unknow');
  	}
  	$this->form = new LibelleAdminForm($object, $this->key);
  	if ($request->isMethod(sfWebRequest::POST)) {
  		$this->form->bind($request->getParameter($this->form->getName()));
  		if ($this->form->isValid()) {
  			$this->form->save();
  			$this->redirect('admin_libelles');
  		}
  	}
  }
}
