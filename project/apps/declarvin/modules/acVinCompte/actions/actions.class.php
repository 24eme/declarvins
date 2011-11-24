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
        $this->forward404Unless($this->contrat = $this->getUser()->getContrat());
        $this->form = new CompteTiersAjoutForm(new CompteTiers(), array('contrat' => $this->contrat));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compteTiers = $this->form->save();
                $this->contrat->setCompte($compteTiers->get('_id'));
                $this->contrat->save();
                $this->redirect('@contrat_etablissement_recapitulatif');
            }
        }
    }

    public function executeLogin(sfWebRequest $request) {
        if ($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("compte")) {
	  $this->redirect('@tiers');
        } elseif ($request->getParameter('ticket')) {
	  /** CAS * */
	  error_reporting(E_ALL);
	  require_once(sfConfig::get('sf_lib_dir') . '/vendor/phpCAS/CAS.class.php');
	  phpCAS::client(CAS_VERSION_2_0, sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
	  phpCAS::setNoCasServerValidation();
	  $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} about to force auth');
	  phpCAS::forceAuthentication();
	  $this->getContext()->getLogger()->debug('{sfCASRequiredFilter} auth is good');
	  /** ***** */
	  $this->getUser()->signIn(phpCAS::getUser());
	  $this->redirect('@tiers');
        } else {
	  $url = sfConfig::get('app_cas_url') . '/login?service=' . $request->getUri();
	  $this->redirect($url);
        }
    }
    
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeLogout(sfWebRequest $request) {
      require_once(sfConfig::get('sf_lib_dir').'/vendor/phpCAS/CAS.class.php');
      $this->getUser()->signOut();
      $url = 'http://'.$request->getHost();
      error_reporting(E_ALL);
      phpCAS::client(CAS_VERSION_2_0,sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
      phpCAS::logoutWithRedirectService($url);
      $this->redirect($url);
    }
    


}
