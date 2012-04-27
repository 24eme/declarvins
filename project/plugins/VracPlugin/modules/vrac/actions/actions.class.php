<?php

/**
 * vrac actions.
 *
 * @package    declarvin
 * @subpackage vrac
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vracActions extends sfActions
{

  public function executeList(sfWebRequest $request)
  {
      $this->historique = new VracHistorique ($this->getUser()->getTiers()->identifiant);
      $this->config = ConfigurationClient::getCurrent();
  }
  
  public function executeHistorique(sfWebRequest $request)
  {
    $this->annee = $request->getParameter('annee');
    $this->historique = new VracHistorique ($this->getUser()->getTiers()->identifiant, $this->annee);
    $this->config = ConfigurationClient::getCurrent();
  }
  
  public function executeSwitch(sfWebRequest $request)
  {
    $this->annee = $request->getParameter('annee');
    $this->id = $request->getParameter('id');
    $vrac = VracClient::getInstance()->find($this->id);
    $actif = ($vrac->actif)? false : true;
    $vrac->set('actif', $actif);
    $vrac->save();
    $this->getUser()->setFlash("notice", 'Le contrat vrac a été modifié avec success.');
    $this->redirect('vrac_historique', array('annee' => $this->annee));
  }
    
}
