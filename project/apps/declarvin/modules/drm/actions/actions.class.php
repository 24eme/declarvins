<?php

/**
 * drm actions.
 *
 * @package    declarvin
 * @subpackage drm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class drmActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward('default', 'module');
  }
  
  /**
   *
   * @param sfWebRequest $request 
   */
  public function executeInit(sfWebRequest $request) {
      $drm = $this->getUser()->getDRM();
      if ($drm->isNew()) {
          $drm->save();
      }
      $this->redirect('drm_informations');
  }
  
 /**
  * Executes mon espace action
  *
  * @param sfRequest $request A request object
  */
  public function executeMonEspace(sfWebRequest $request)
  {
      
  }
 /**
  * Executes informations action
  *
  * @param sfRequest $request A request object
  */
  public function executeInformations(sfWebRequest $request)
  {
      
  }
 /**
  * Executes mouvements generaux action
  *
  * @param sfRequest $request A request object
  */
  public function executeMouvementsGeneraux(sfWebRequest $request)
  {
    
  }
 /**
  * Executes evolution action
  *
  * @param sfRequest $request A request object
  */
  public function executeEvolution(sfWebRequest $request)
  {
    
  }
}
