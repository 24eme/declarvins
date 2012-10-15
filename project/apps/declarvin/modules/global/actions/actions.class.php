<?php

/**
 * global actions.
 *
 * @package    declarvin
 * @subpackage global
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class globalActions extends sfActions
{
    public function executeSecure() {
        //if ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN))
        //return $this->redirect("@tiers");
    }
}
