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
      $this->setTemplate('index');
  }
  
  /*
   * 
   */
  public function executeCompte(sfWebRequest $request)
  {
     $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
     $this->form = new CompteModificationDroitForm($this->compte);
  
  //   print_r($request->getParameterHolder()->getAll());
     
     if($request->isMethod(sfWebRequest::POST))
     {
     $this->form->bind($request->getParameter($this->form->getName()));
     if($this->form->isValid())
        {
         $this->form->save();
         $this->redirect(array('sf_route' => 'compte', 'login' => $this->compte->login));
        }
     }
  }
  
}
