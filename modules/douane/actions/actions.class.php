<?php

/**
 * douane actions.
 *
 * @package    declarvin
 * @subpackage drm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class douaneActions extends sfActions
{
  
  public function executeIndex(sfWebRequest $request)
  {
      $this->douanes = DouaneAllView::getInstance()->findAll();
  }
  
  public function executeEtablissements(sfWebRequest $request)
  {
  	  $this->douane = $this->findDouaneById($request->getParameter('id'));
      $this->etablissements = EtablissementDouaneView::getInstance()->findByDouane($this->douane->nom);
  }
  
  public function executeNouveau(sfWebRequest $request)
  {
      $this->form = new DouaneForm(new Douane());
      if ($request->isMethod(sfWebRequest::POST)) {
      	$this->form->bind($request->getParameter($this->form->getName()));
      	if ($this->form->isValid()) {
      		$this->form->save();
      		$this->getUser()->setFlash('notice', 'Ajout effectuée avec succès');
      		$this->redirect('@admin_douanes');
      	}
      }
  }
  
  public function executeModification(sfWebRequest $request)
  {
      $douane = $this->findDouaneById($request->getParameter('id'));
      $this->form = new DouaneForm($douane);
      if ($request->isMethod(sfWebRequest::POST)) {
      	$this->form->bind($request->getParameter($this->form->getName()));
      	if ($this->form->isValid()) {
      		$this->form->save();
      		$this->getUser()->setFlash('notice', 'Modification effectuée avec succès');
      		$this->redirect('@admin_douanes');
      	}
      }
  }
  
  public function executeActiver(sfWebRequest $request)
  {
      $douane = $this->findDouaneById($request->getParameter('id'));
      $douane->setStatut(Douane::STATUT_ACTIF);
      $douane->save();
      $this->getUser()->setFlash('notice', 'Modification effectuée avec succès');
      $this->redirect('@admin_douanes');

  }
  
  public function executeDesactiver(sfWebRequest $request)
  {
      $douane = $this->findDouaneById($request->getParameter('id'));
      $douane->setStatut(Douane::STATUT_INACTIF);
      $douane->save();
      $this->getUser()->setFlash('notice', 'Modification effectuée avec succès');
      $this->redirect('@admin_douanes');

  }
  
  protected function findDouaneById($id)
  {
  	$this->forward404Unless($id);
    if (!$douane = DouaneClient::getInstance()->find($id)) {
    	throw new sfException('Aucun document d\'id '.$id);
    }
    return $douane;
  }

}
