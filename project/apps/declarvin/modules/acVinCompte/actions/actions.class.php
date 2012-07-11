<?php
require_once sfConfig::get('sf_plugins_dir').'/acVinComptePlugin/modules/acVinCompte/lib/BaseacVinCompteActions.class.php';
/**
 * compte actions.
 *
 * @package    declarvin
 * @subpackage compte
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class acVinCompteActions extends BaseacVinCompteActions {

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeNouveau(sfWebRequest $request) {
        $this->forward404Unless($this->contrat = ContratClient::getInstance()->find('CONTRAT-'.$request->getParameter('nocontrat')));
        $this->form = new CompteTiersAjoutForm(_CompteClient::getInstance()->find('COMPTE-'.$request->getParameter('nocontrat')), array('contrat' => $this->contrat));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compteTiers = $this->form->save();
                $newCompteTiers = clone $compteTiers;
                $compteTiers->delete();
                $newCompteTiers->_id = 'COMPTE-'.$newCompteTiers->login;
                //$newCompteTiers->statut = _Compte::STATUT_ACTIVE;
                $newCompteTiers->save();
                $this->contrat->setCompte($newCompteTiers->get('_id'));
                $this->contrat->save();
                $this->getUser()->signOut();
                $this->getUser()->signIn($newCompteTiers->login);
                $this->getUser()->setFlash('notice', 'Création de compte validée');
	  			$this->redirect('@tiers');
            }
        }
    }
    public function executeRedefinitionPassword(sfWebRequest $request) {
    	$this->forward404Unless($login = $request->getParameter('login'));
        $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($request->getParameter('login')));
        $this->form = new CompteTiersPasswordForm($this->compte);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compteTiers = $this->form->save();
                $this->getUser()->signOut();
                $this->getUser()->signIn($compteTiers->login);
                $this->getUser()->setFlash('notice', 'Redéfinition du mot de passe effectuée');
	  			$this->redirect('@tiers');
            }
        }
    }

    public function executeLogin(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("compte")) {
	  		$this->redirect('@tiers');
        } elseif ($request->getParameter('ticket')) {
			/** CAS * */
			acPhpCas::client();
			acPhpCas::setNoCasServerValidation();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} about to force auth');
			acPhpCas::forceAuthentication();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} auth is good');
			/** ***** */
			$this->getUser()->signIn(phpCAS::getUser());
			$this->redirect('@tiers');
        } else {
        	//$this->getUser()->signIn('autologin');
	        //$this->redirect('@tiers');
	  		$url = sfConfig::get('app_cas_url') . '/login?service=' . $request->getUri();
	  		$this->redirect($url);
        }
    }
    
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeLogout(sfWebRequest $request) {
      $this->getUser()->signOut();
      $url = 'http://'.$request->getHost();
      acPhpCas::client();
      phpCAS::logoutWithRedirectService($url);
      $this->redirect($url);
    }
    


}
