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
      $this->tiers = $this->getUser()->getTiers();
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
    $this->configuration = ConfigurationClient::getCurrent();
  }
 /**
  * Executes historique action
  *
  * @param sfRequest $request A request object
  */
  public function executeHistorique(sfWebRequest $request)
  {
    $this->annee = $request->getParameter('annee');
    // On recupere les annees
    $drms = acCouchdbManager::getClient()->startkey(array($this->getUser()->getTiers()->identifiant, null))
    										   ->endkey(array($this->getUser()->getTiers()->identifiant, array()))
    										   ->group(true)
    										   ->group_level(2)
    										   ->getView("drm", "all")->rows;
  	$this->annees = array();
    foreach ($drms as $drm) {
  		$this->annees[] = $drm->key[1];
  	}
  	rsort($this->annees);
  	// -------------------------------
  	if (!$this->annee) {
  		$this->annee = $this->annees[0];
  	}
  	$this->anneeFixe = $this->annees[0];

  	$drms = acCouchdbManager::getClient()->startkey(array($this->getUser()->getTiers()->identifiant, $this->annee, null))
    										  ->endkey(array($this->getUser()->getTiers()->identifiant, $this->annee, array()))
    										  ->reduce(false)
    										  ->executeView("drm", "all", acCouchdbClient::HYDRATE_DOCUMENT);
    $this->drms = $drms->getDatas();
    krsort($this->drms);
  }
  
}
