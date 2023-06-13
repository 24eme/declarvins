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
  		$this->redirect('@etablissement_login');
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementDRMLogin(sfWebRequest $request)
  {
  	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
  	$admin = (int)$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);

    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}

      if ($this->form->isValid()) {
          if ($this->form->getEtablissement()->hasDroit(EtablissementDroit::DROIT_DRM_PAPIER)) {
        	return $this->redirect("drm_mon_espace", $this->form->getEtablissement());
        } else {
            return $this->redirect("profil", $this->form->getEtablissement());
        }
      }
    }
  }
  public function executeEtablissementSubventionLogin(sfWebRequest $request)
  {
  	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
  	$admin = (int)$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}

      if ($this->form->isValid()) {
        	return $this->redirect("subvention_etablissement", $this->form->getEtablissement());
      }
    }
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementDSNegoceLogin(sfWebRequest $request)
  {
  	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
  	$admin = (int)$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'), array(), array('familles' => array("negociant")));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}

      if ($this->form->isValid()) {
        	return $this->redirect("dsnegoceupload_mon_espace", $this->form->getEtablissement());
      }
    }
  }
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeEtablissementDAIDSLogin(sfWebRequest $request)
  {
  	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'), array(), array('familles' => array("producteur")));
    if ($request->isMethod(sfWebRequest::POST)) {
    	if ($request->getParameterHolder()->has('etablissement_selection_nav')) {
    		$this->form->bind($request->getParameter('etablissement_selection_nav'));
    	} else {
      	$this->form->bind($request->getParameter($this->form->getName()));
    	}

      if ($this->form->isValid()) {
        	return $this->redirect("daids_mon_espace", $this->form->getEtablissement());
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
  	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
  	$admin = (int)$this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN);
    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'), array(), array('admin' => $admin));
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

  public function executeEtablissementFacturesLogin(sfWebRequest $request)
  {
    $this->form = new FactureSelectionForm();
    if ($request->isMethod(sfWebRequest::POST)) {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid()) {
        	return $this->redirect("facture_societe", array('identifiant' => $this->form->getEtablissement()->identifiant));
      }
    }
  }

  public function executeEtablissementSV12Login(sfWebRequest $request)
  {
      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
      $familles = array(
    	EtablissementFamilles::FAMILLE_PRODUCTEUR => implode("|",array(EtablissementFamilles::SOUS_FAMILLE_CAVE_PARTICULIERE, EtablissementFamilles::SOUS_FAMILLE_CAVE_COOPERATIVE)),
        EtablissementFamilles::FAMILLE_NEGOCIANT => EtablissementFamilles::SOUS_FAMILLE_VINIFICATEUR,
    );

    $this->form = new EtablissementSelectionForm($this->interpro->get('_id'), array(), array('sous_familles' => $familles));
    if ($request->isMethod(sfWebRequest::POST)) {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid()) {
        	return $this->redirect("sv12_etablissement", array('identifiant' => $this->form->getEtablissement()->identifiant));
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
    //$this->droits = ConfigurationClient::getCurrent()->droits;
    $this->labels = ConfigurationClient::getCurrent()->labels;
    $this->controles = ControlesClient::getInstance()->findAll();
    $this->configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getUser()->getCompte()->getGerantInterpro()->getKey());
  }
  public function executeLibelleModification(sfWebRequest $request)
  {
  	$this->forward404Unless($this->key4Route = $request->getParameter('key'));
    $this->key = str_replace('@', '/', $this->key4Route);
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
  		$object = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($this->getUser()->getCompte()->getGerantInterpro()->getKey());
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
