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
                $newCompteTiers->statut = _Compte::STATUT_ACTIF;
                $newCompteTiers->save();
                foreach ($newCompteTiers->tiers as $etablissement_id => $values) {
                	$etablissement = EtablissementClient::getInstance()->find($etablissement_id);
                	$etablissement->compte = 'COMPTE-'.$newCompteTiers->login;
                	$etablissement->save();
                }
           		$ldap = new Ldap();
           		$ldap->saveCompte($newCompteTiers);
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
           		$ldap = new Ldap();
           		$ldap->saveCompte($compteTiers);
                $this->getUser()->signOut();
                $this->getUser()->signIn($compteTiers->login);
                $this->getUser()->setFlash('notice', 'Redéfinition du mot de passe effectuée');
	  			$this->redirect('@tiers');
            }
        }
    }

    public function executeLogin(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("compte")) {
	  		$this->redirectAfterLogin();
        } elseif ($request->getParameter('ticket')) {
			/** CAS * */
			acPhpCas::client();
			acPhpCas::setNoCasServerValidation();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} about to force auth');
			acPhpCas::forceAuthentication();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} auth is good');
			/** ***** */
			$this->getUser()->signIn(phpCAS::getUser());
			
            return $this->redirectAfterLogin();
        } else {
            if(sfConfig::has('app_autologin') && sfConfig::get('app_autologin')) {
        	   $this->getUser()->signIn(sfConfig::get('app_autologin'));
	           
               return $this->redirectAfterLogin();
            }

	  		$url = sfConfig::get('app_ac_php_cas_url') . '/login?service=' . $request->getUri();
	  		
            return $this->redirect($url);
        }
    }

    protected function redirectAfterLogin() {
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {

            return $this->redirect('@admin');
        }

        return $this->redirect('@tiers');
    }
    
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeLogout(sfWebRequest $request) {
        $this->getUser()->signOut();
        
        if(sfConfig::has('app_autologin') && sfConfig::get('app_autologin')) {
            
            return $this->redirect('login');
        }

        $url = 'http://'.$request->getHost();
        acPhpCas::client();
        phpCAS::logoutWithRedirectService($url);
        
        return $this->redirect($url);
    }
    


}
