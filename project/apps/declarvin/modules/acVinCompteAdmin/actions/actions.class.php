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
     $this->form = new CompteModificationDroitForm($this->compte);       
     if($request->isMethod(sfWebRequest::POST))
     {
     $this->form->bind($request->getParameter($this->form->getName()));
     if($this->form->isValid())
        {
         $this->form->save();
         $ldap = new Ldap();
         $ldap->saveCompte($this->compte);
         $this->getUser()->setFlash('notice', 'Modifications effectuées avec succès');
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
     Email::getInstance()->sendRedefinitionMotDePasse($compte, $compte->email);
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
           $this->getUser()->setFlash('notice', 'Création de compte validée');
           $this->redirect(array('sf_route' => 'compte_modification', 'login' => $this->compte->login));
           }
        }                  
    }


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