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
  	$this->forward404Unless($noeud = $request->getParameter('noeud', null));
  	$this->forward404Unless($hash = $request->getParameter('hash', null));
  	$this->nbDepartement = $request->getParameter('nb_departement', null);
  	$this->nbDouane = $request->getParameter('nb_douane', null);
  	$this->nbCvo = $request->getParameter('nb_cvo', null);
  	$this->nbLabel = $request->getParameter('nb_label', null);
  	$hash = str_replace('-', '/', $hash);
  	$object = ConfigurationClient::getCurrent()->getOrAdd($hash);
  	if ($pile = $this->getUser()->hasAttribute('pile_noeud')) {
  		$pile = $this->getUser()->hasAttribute('pile_noeud');
  		$arborescence = ConfigurationProduit::getArborescence();
  		foreach ($arborescence as $produit) {
  			if (isset($pile[$produit])) {
  				$object = $object->getOrAdd($produit)->add(Configuration::DEFAULT_KEY);
  				$object->set('libelle', $pile[$produit]);
  				$noeud = $object->getTypeNoeud();
  				break;
  			}
  		}
  	}
  	$object = $object->__get($noeud);
  	$this->form = new ProduitDefinitionForm($object, array('nbDepartement' => $this->nbDepartement, 'nbDouane' => $this->nbDouane, 'nbCvo' => $this->nbCvo, 'nbLabel' => $this->nbLabel));
  	$this->form->setHash($hash);
  	
  	if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$this->form->save();
			$this->checkKeys($hash);
			$this->getUser()->setFlash("notice", 'Le produit a été modifié avec success.');
			if ($pile) {
		  		$arborescence = ConfigurationProduit::getArborescence();
		  		foreach ($arborescence as $produit) {
		  			if (isset($pile[$produit])) {
		  				unset($pile[$produit]);
		  				if ($pile) {
		  					$this->getUser()->setAttribute('pile_noeud', $pile);
		  					$this->redirect('produit_modification', array('noeud' => $object->getTypeNoeud(), 'hash' => str_replace('/', '-', $hash)));
		  				} else {
		  					$this->redirect('produits');
		  				}
		  			} else {
		  				$this->redirect('produits');
		  			}
		  		}
		  	}
		  	$this->redirect('produits');
		}
    }
  }
  public function executeNouveau(sfWebRequest $request)
  {
  	$this->forward404Unless($this->interpro = $this->getUser()->getInterpro());
  	$configuration = ConfigurationClient::getCurrent();
  	$this->form = new ProduitNouveauForm($configuration, $this->interpro->_id);
  	if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
		if ($this->form->isValid()) {
			$result = $this->form->save();
			$hash = $result['hash'];
			$noeud = $result['noeud'];
			$values = $result['values'];
			if ($values) {
				$this->getUser()->setAttribute('pile_noeud', $values);
			}
			$this->getUser()->setFlash("notice", 'Le produit a été ajouté avec success.');
			$this->redirect('produit_modification', array('noeud' => $noeud, 'hash' => str_replace('/', '-', $hash)));
		}
    }
  }
  private function checkKeys($hash) {
  
    	/*if ($this->getObject()->getKey() == Configuration::DEFAULT_KEY) {
    		$hash = $this->getObject()->getHash();
    		$newHash = explode('/', $hash);
    		$newHash[count($newHash) - 1] = strtoupper($values['code']);
    		$newHash = implode('/', $newHash);
    		$this->getObject()->getDocument()->moveAndClean($hash, $newHash);
    	}*/
  }
}
