<?php

/**
 * CompteOIOC actions.
 *
 * @package    declarvin
 * @subpackage CompteOIOC
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class CompteOIOCActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
  		return $this->redirect('validation_login');
  	}
  	$this->comptes = _CompteClient::getInstance()->findAllOIOCByInterpo($this->getUser()->getCompte()->getGerantInterpro()->_id);
  }
  
  public function executeCompteSuppression(sfWebRequest $request)
  {
  	$this->forward404Unless($compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
  	$ldap = new Ldap();
  	$ldap->deleteCompte($compte);
  	$compte->statut = _Compte::STATUT_ARCHIVE;
  	$compte->save();
  	$this->getUser()->setFlash('notice', 'Compte supprimé avec succès');
  	$this->redirect("@oioc_comptes");
  }
  
  public function executeCompteCreation(sfWebRequest $request)
  {
  	$this->forward404Unless($compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
  	$ldap = new Ldap();
  	$ldap->saveCompte($compte);
  	$compte->statut = _Compte::STATUT_INSCRIT;
  	$compte->save();
  	$this->getUser()->setFlash('notice', 'Compte activé avec succès');
  	$this->redirect("@oioc_comptes");
  }
  
  public function executeCompteModification(sfWebRequest $request)
  {
     $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     $this->form = new CompteOIOCModificationForm($this->compte);       
     if($request->isMethod(sfWebRequest::POST))
     {
     	$this->form->bind($request->getParameter($this->form->getName()));
     	if($this->form->isValid())
        {
         $this->form->save();
         $ldap = new Ldap();
         $ldap->saveCompte($this->compte);
         $this->getUser()->setFlash('notice', 'Modifications effectuées avec succès');
         $this->redirect(array('sf_route' => 'compte_oioc_modification', 'login' => $this->compte->login));
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
     $this->redirect(array('sf_route' => 'compte_oioc_modification', 'login' => $compte->login));
  }
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeCompteAjout(sfWebRequest $request) 
    {
      $this->compte = new CompteOIOC();
      $this->compte->statut = _Compte::STATUT_INSCRIT;
   	  $this->compte->interpro = array($this->getUser()->getCompte()->getGerantInterpro()->_id => array('statut' => _Compte::STATUT_VALIDE));
      $this->form = new CompteOIOCModificationForm($this->compte);
      if($request->isMethod(sfWebRequest::POST))
        {           
           $this->form->bind($request->getParameter($this->form->getName()));
           if($this->form->isValid())
           {
           	$this->form->save();
           	$ldap = new Ldap();
           	$ldap->saveCompte($this->compte);
           	$this->getUser()->setFlash('notice', 'Création de compte validée');
           	$this->redirect('oioc_comptes');
           }
        }                  
    }
}