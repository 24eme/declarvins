<?php

/**
 * produit actions.
 *
 * @package    declarvin
 * @subpackage produit
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class produitActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
	$this->produits = ConfigurationClient::getInstance()->findProduitsForAdmin($this->interpro->_id);
  }
  public function executeModification(sfWebRequest $request)
  {
  	//$this->forward404Unless($request->isXmlHttpRequest());
  	$this->forward404Unless($noeud = $request->getParameter('noeud', null));
  	$this->forward404Unless($hash = $request->getParameter('hash', null));
  	$this->nbDepartement = $request->getParameter('nb_departement', null);
  	$this->nbDouane = $request->getParameter('nb_douane', null);
  	$this->nbCvo = $request->getParameter('nb_cvo', null);
  	$this->nbLabel = $request->getParameter('nb_label', null);
  	$hash = str_replace('-', '/', $hash);
  	$object = ConfigurationClient::getCurrent()->get($hash);
  	$object = $object->__get($noeud);
  	$this->form = new ProduitDefinitionForm($object, array('nbDepartement' => $this->nbDepartement, 'nbDouane' => $this->nbDouane, 'nbCvo' => $this->nbCvo, 'nbLabel' => $this->nbLabel));
  	$this->form->setHash($hash);
  	
  	if ($request->isMethod(sfWebRequest::POST)) {
    	//$this->getResponse()->setContentType('text/json');
        $this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$this->form->save();
			$this->getUser()->setFlash("notice", 'Le produit a été modifié avec success.');
			//return $this->renderText(json_encode(array("success" => true, "url" => $this->generateUrl('produits'))));
		} else {
			//return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('form', array('form' => $form)))));
		}
    }
    //return $this->renderText($this->getPartial('popup', array('form' => $form)));
  }
}
