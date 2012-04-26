<?php

/**
 * user actions.
 *
 * @package    declarvin
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function login(sfWebRequest $request)
  {
      $this->getUser()->signIn($_SERVER['PHP_AUTH_USER']);
  }
}
