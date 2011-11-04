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
	if (!$this->getUser()->hasCredential('tiers') && $this->getUser()->hasCredential('compte-tiers')) {
		return $this->redirect("@tiers");
	} elseif(!$this->getUser()->hasCredential('tiers')) {
		$this->getUser()->signOut();
		return $this->redirect("@admin");
	} else {
		return $this->redirect("@mon_espace");
	}
  }
}
