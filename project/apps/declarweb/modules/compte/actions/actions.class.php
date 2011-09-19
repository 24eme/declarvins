<?php

/**
 * compte actions.
 *
 * @package    declarweb
 * @subpackage compte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class compteActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->init($request);

        $this->action = 'index';
        if ($request->isMethod(sfWebRequest::POST) && $request->getParameter($this->form->getName())) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->compte = $this->form->save();
                $this->getUser()->setFlash('maj', 'Les identifiants ont bien été mis à jour.');
                $this->redirect('@compte');
            }
        }
    }
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeNewChai(sfWebRequest $request) {
        $this->init($request);
        $this->action = 'new';
        $chai = new Chai();
        $chai->setInterpro($this->interpro->get('_id'));
        $chai->setCompte($this->compte->get('_id'));
        $this->formChai = new ChaiForm($chai);
        if ($request->isMethod(sfWebRequest::POST)) {

            $this->formChai->bind($request->getParameter($this->formChai->getName()));
            if ($this->formChai->isValid()) {
                $this->chai = $this->formChai->save();
                $tiers = $this->compte->tiers->add($this->chai->get('_id'));
                $tiers->setId($this->chai->get('_id'));
                $tiers->setType('Chai');
                $tiers->setNom($this->chai->getNom());
                $tiers->setInterpro($this->interpro->get('_id'));
                if (!$this->compte->getInterpro()->exist($this->interpro->get('_id'))) {
                    $this->compte->interpro->add($this->interpro->get('_id'))->setStatut(_Compte::STATUT_VALIDATION_ATTENTE);
                }
                $this->compte->save();
                $this->getUser()->setFlash('chai', 'Le chai bien été ajouté.');
                $this->redirect('@compte');
            }
        }
        $this->setTemplate('index');
    }
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeUpdateChai(sfWebRequest $request) {
        $this->init($request);
        $this->action = 'update';
        $this->forward404Unless($chai = sfCouchdbManager::getClient('Chai')->retrieveByIdentifiant($this->identifiant));
        $this->forward404Unless($chai->getInterpro() == $this->interpro->get('_id'));
        $this->formChai = new ChaiModificationForm($chai);
        if ($request->isMethod(sfWebRequest::POST)) {

            $this->formChai->bind($request->getParameter($this->formChai->getName()));
            if ($this->formChai->isValid()) {
                $this->chai = $this->formChai->save();
                $this->compte->tiers->get($this->chai->get('_id'))->set('nom', $this->chai->getNom());
                $this->compte->save();
                $this->getUser()->setFlash('chai', 'Le chai bien été mis à jour.');
                $this->redirect('@compte');
            }
        }
        
        $this->setTemplate('index');
    }
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeLiaisonInterpro(sfWebRequest $request) {
        $this->init($request);
        $this->action = 'liaison';
        
        if ($request->isMethod(sfWebRequest::POST)) {

            $this->formLiaison->bind($request->getParameter($this->formLiaison->getName()));
            if ($this->formLiaison->isValid()) {
                $this->formLiaison->save();
                $this->getUser()->setFlash('general', 'Liaisons interpro faites');
                $this->redirect('@compte');
            }
        }
        
        $this->setTemplate('index');
    }
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeValidation(sfWebRequest $request) {       
      $this->forward404Unless($interpro_id = $request->getParameter('interpro_id'));
      $this->forward404Unless($request->isMethod(sfWebRequest::POST));
      $this->compte = $this->getUser()->getCompte();
      if (!$this->compte->interpro->exist($interpro_id)) {
	$this->compte->interpro->add($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
      } else {
	$this->compte->interpro->get($interpro_id)->setStatut(_Compte::STATUT_VALIDATION_VALIDE);
      }
      $this->compte->save();
      $this->redirect('@compte');
    }
    
    /**
     * 
     */
    public function init(sfWebRequest $request) {
        $this->interpro = $this->getUser()->getInterpro();
        $this->compte = $this->getUser()->getCompte();
        $this->form = new CompteModificationForm($this->compte);
        $this->chais = $this->compte->getTiersCollection();
        $this->identifiant = $request->getParameter('id', null);
        $this->formLiaison = new LiaisonInterproForm($this->compte);
    }

}
