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
}
