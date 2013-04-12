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
        $this->forward404Unless($this->compte = _CompteClient::getInstance()->find('COMPTE-'.$request->getParameter('nocontrat')));
        $this->form = new CompteTiersAjoutForm($this->compte, array('contrat' => $this->contrat));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compteTiers = $this->form->save();
                $newCompteTiers = clone $compteTiers;
                $compteTiers->delete();
                $newCompteTiers->_id = 'COMPTE-'.$newCompteTiers->login;
                $newCompteTiers->statut = _Compte::STATUT_INSCRIT;
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
                $this->getUser()->setFlash('notice', 'Création de compte validée');
	  			$this->redirect('compte_valide');
            }
        }
    }
    public function executeValide(sfWebRequest $request) {
    	
    }
    public function executeCompteInexistant(sfWebRequest $request) {
    	$this->compte = $request->getParameter('login', null);
    }
    public function executeComptePartenaire(sfWebRequest $request) {
    	
    }
    public function executeAccesInterdit(sfWebRequest $request) {
    	
    }
    public function executeRedefinitionPassword(sfWebRequest $request) {
    	$this->forward404Unless($this->login = $request->getParameter('login'));
        $this->forward404Unless($this->compte = _CompteClient::getInstance()->retrieveByLogin($this->login));
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
    
    public function executeLostPassword(sfWebRequest $request) {
        $this->form = new CompteLostPasswordForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $login = $this->form->getValue('login');
                $compte = _CompteClient::getInstance()->retrieveByLogin($login);
     			Email::getInstance()->sendRedefinitionMotDePasse($compte, $compte->email);
     			$this->getUser()->setFlash('notice', 'Une demande de redéfinition de votre mot de passe vous a été envoyé');
     			$this->redirect('@compte_lost_password');
            }
        }
    }

    public function executeLogin(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("compte")) {
	  		$this->redirectAfterLogin();
        } elseif ($ticket = $request->getParameter('ticket')) {
			/** CAS * */
			acPhpCas::client();
			acPhpCas::setNoCasServerValidation();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} about to force auth');
			acPhpCas::forceAuthentication();
			$this->getContext()->getLogger()->debug('{sfCASRequiredFilter} auth is good');
			/** ***** */
			try {
				$this->getUser()->signIn(phpCAS::getUser());
			} catch (sfException $e) {
				$this->redirect('compte_inexistant', array('login' => phpCAS::getUser()));
			}
			if ($this->getUser()->getCompte()->type == ComptePartenaire::COMPTE_TYPE_PARTENAIRE) {
				$this->getUser()->signOut();
				$this->redirect('@compte_partenaire');
			}
			if ($service = $request->getParameter('service')) {
				return $this->redirect($service);
			}
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
