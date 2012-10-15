<?php

/**
 * acVinCompteAdmin actions.
 *
 * @package    declarvin
 * @subpackage acVinCompteAdmin
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class acVinCompteAdminActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->form = new CompteSelectionForm();
  	if ($request->isMethod(sfWebRequest::POST)) {
  		$this->form->bind($request->getParameter($this->form->getName()));
  		if ($this->form->isValid()) {
  			$values = $this->form->getValues();
  			$login = $values['compte'];
  			return $this->redirect("compte_modification", array('login' => $login));
  		}
  	}
  }
  
  /*
   * 
   */
  public function executeCompteModification(sfWebRequest $request)
  {
     $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     if ($contrat = $this->compte->contrat) {
        //$this->redirect('validation_fiche', array('num_contrat' => $this->compte->getContratObject()->no_contrat));
     }
     
     $this->form = new CompteModificationDroitForm($this->compte);       
     if($request->isMethod(sfWebRequest::POST))
     {
     $this->form->bind($request->getParameter($this->form->getName()));
     if($this->form->isValid())
        {
         $this->form->save();
         $ldap = new Ldap();
         $ldap->saveCompte($this->compte);
         $this->redirect(array('sf_route' => 'compte_modification', 'login' => $this->compte->login));
        }
     }
  }
  
  /*
   * 
   */
  public function executeRedefinitionPassword(sfWebRequest $request)
  {
     $this->forward404Unless($compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     $mess = 'Bonjour '.$compte->nom.' '.$compte->prenom.',
    	
Une procédure de redéfinition de mot de passe pour votre compte a été demandée.

Vous pouvez le modifier en suivant le lien suivant : <a href="'.$this->getController()->genUrl(array('sf_route' => 'compte_password', 'login' => $compte->login), false).'">Redéfinition de mon mot de passe</a>

Cordialement,

DéclarVins';
        $message = Swift_Message::newInstance()
                ->setFrom(array(sfConfig::get('app_password_from_email') => sfConfig::get('app_password_from_name')))
                ->setTo($this->getUser()->getCompte()->email)
                ->setSubject(sfConfig::get('app_password_subject'))
                ->setBody($mess);        
        $this->getMailer()->send($message);
        $this->getUser()->setFlash('notice', 'Demande de redéfinition du mot de passe envoyée');
        $this->redirect(array('sf_route' => 'compte_modification', 'login' => $compte->login));
  }
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeCompteAjout(sfWebRequest $request) 
    {
      $this->compte = new CompteVirtuel(); 
      $this->compte->interpro = $this->getUser()->getCompte()->interpro;
      $this->form = new CompteModificationDroitForm($this->compte);
      if($request->isMethod(sfWebRequest::POST))
        {           
           $this->form->bind($request->getParameter($this->form->getName()));
           if($this->form->isValid())
           {
           $this->form->save();
           $ldap = new Ldap();
           $ldap->saveCompte($this->compte);
           $this->redirect(array('sf_route' => 'compte_modification', 'login' => $this->compte->login));
           }
        }                  
    }
   /*
    public function executeCompteRecap(sfWebRequest $request)
    {
        $this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login'));
        $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
        $this->form = new CompteRecapForm($this->compte);       
        if($request->isMethod(sfWebRequest::POST))
        {
        $this->form->bind($request->getParameter($this->form->getName()));
        if($this->form->isValid())
            {
            $this->form->save();
            $this->redirect(array('sf_route' => 'compte_modification', 'login' => $this->compte->login));
            }
        
        }
    }    
    */

  public function executeCompteAutocomplete(sfWebRequest $request) {
    $comptes = _CompteClient::getInstance()->findAllByInterpo($this->getUser()->getCompte()->getGerantInterpro()->get('_id'))->rows;
    $json = array();
    $limit = $request->getParameter('limit', 100);
    foreach($comptes as $key => $compte) {
      $text = _CompteClient::getInstance()->makeLibelle($compte->key);
     
      if (Search::matchTerm($request->getParameter('q'), $text)) {
        $json[$compte->key[_CompteClient::KEY_LOGIN]] = _CompteClient::getInstance()->makeLibelle($compte->key);
      }

      if (count($json) >= $limit) {
        break;
      }
    }

    return $this->renderText(json_encode($json));
  }
}