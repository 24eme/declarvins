<?php

/**
 * alerte actions.
 *
 * @package    declarvin
 * @subpackage alerte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class alerteActions extends sfActions
{
  public function executeDrmManquante(sfWebRequest $request) {
      $this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
	  $this->alertes = AlertesAllView::getInstance()->findByType($this->interpro->_id, DRMsManquantes::ALERTE_DOC_ID);
  }
  
}
