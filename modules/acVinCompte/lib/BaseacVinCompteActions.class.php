<?php

/* This file is part of the acVinComptePlugin package.
 * Copyright (c) 2011 Actualys
 * Authors :	
 * Tangui Morlier <tangui@tangui.eu.org>
 * Charlotte De Vichet <c.devichet@gmail.com>
 * Vincent Laurent <vince.laurent@gmail.com>
 * Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * acVinCompte plugin.
 * 
 * @package    acVinComptePlugin
 * @subpackage lib
 * @author     Tangui Morlier <tangui@tangui.eu.org>
 * @author     Charlotte De Vichet <c.devichet@gmail.com>
 * @author     Vincent Laurent <vince.laurent@gmail.com>
 * @author     Jean-Baptiste Le Metayer <lemetayer.jb@gmail.com>
 * @version    0.1
 */
class BaseacVinCompteActions extends sfActions
{
	/**
     *
     * @param sfWebRequest $request
     */
    public function executeAcVinCompteLogin(sfWebRequest $request) 
    {
        if ($this->getUser()->isAuthenticated() && $this->getUser()->hasCredential("compte")) {
            $this->redirect(sfConfig::get('app_ac_vin_compte_login_redirect'));
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
            $this->redirect(sfConfig::get('app_ac_vin_compte_login_redirect'));
        } else {
            $url = sfConfig::get('app_cas_url') . '/login?service=' . $request->getUri();
            $this->redirect($url);
        }
    }
    
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeAcVinCompteLogout(sfWebRequest $request) 
    {
        require_once(sfConfig::get('sf_lib_dir').'/vendor/phpCAS/CAS.class.php');
        $this->getUser()->signOut();
        $url = 'http://'.$request->getHost();
        error_reporting(E_ALL);
        phpCAS::client(CAS_VERSION_2_0,sfConfig::get('app_cas_domain'), sfConfig::get('app_cas_port'), sfConfig::get('app_cas_path'), false);
        phpCAS::logoutWithRedirectService($url);
        $this->redirect($url);
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeAcVinCompteFirst(sfWebRequest $request) 
    {
        $this->form = new CompteLoginFirstForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->getUser()->signInFirst($this->form->getValue('compte'));
                $this->redirect('@ac_vin_compte_creation');
            }
        }
    }

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeAcVinCompteCreation(sfWebRequest $request) 
    {
        $this->compte = $this->getUser()->getCompte();
        $this->forward404Unless($this->compte->getStatus() == _Compte::STATUS_NOUVEAU);

        $this->form = new CreationCompteForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->compte = $this->form->save();
                try {
                	$infos = sfConfig::get('app_ac_vin_compte_creation_email');
                    $message = $this->getMailer()->composeAndSend(array($infos['from_email'] => $infos['from_name']), $this->compte->email, $infos['subject'], $this->getPartial('acVinCompte/acVinCompteCreationEmail', array('compte' => $this->compte)));
                    $this->getUser()->setFlash('confirmation', "Votre compte a bien été créé.");
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error', "Problème de configuration : l'email n'a pu être envoyé");
                }
                $this->redirect(sfConfig::get('app_ac_vin_compte_creation_redirect'));
            }
        }
    }

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeAcVinCompteModificationOublie(sfWebRequest $request) 
    {
        $this->compte = $this->getUser()->getCompte();
        $this->forward404Unless($this->compte->getStatus() == _Compte::STATUS_MOT_DE_PASSE_OUBLIE);

        $this->form = new CompteModificationOublieForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->compte = $this->form->save();
                try {
                	$infos = sfConfig::get('app_ac_vin_compte_modification_oublie_email');
                    $message = $this->getMailer()->composeAndSend(array($infos['from_email'] => $infos['from_name']), $this->compte->email, $infos['subject'], $this->getPartial('acVinCompte/acVinCompteModificationOublieEmail', array('compte' => $this->compte)));
                    $this->getUser()->setFlash('confirmation', "Votre mot de passe a bien été modifié.");
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error', "Problème de configuration : l'email n'a pu être envoyé");
                }
                $this->redirect(sfConfig::get('app_ac_vin_compte_modification_oublie_redirect'));
            }
        }
    }

    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeAcVinCompteModification(sfWebRequest $request) 
    {
        $this->compte = $this->getUser()->getCompte();
        $this->forward404Unless(in_array($this->compte->getStatus(), array(_Compte::STATUS_MOT_DE_PASSE_OUBLIE, _Compte::STATUS_INSCRIT)));

        $this->form = new CompteModificationForm($this->compte);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->compte = $this->form->save();
                $this->getUser()->setFlash('maj', 'Vos identifiants ont bien été mis à jour.');
                $this->redirect('@ac_vin_compte_modification');
            }
        }
    }

    public function executeAcVinCompteMotDePasseOublieLogin(sfWebRequest $request) 
    {
        $this->forward404Unless($compte = acCouchdbManager::getClient('_Compte')->retrieveByLogin($request->getParameter('login', null)));
        $this->forward404Unless($compte->mot_de_passe == '{OUBLIE}' . $request->getParameter('mdp', null));
        $this->getUser()->signInFirst($compte);
        $this->redirect('@ac_vin_compte_modification_oublie');
    }

    public function executeAcVinCompteMotDePasseOublie(sfWebRequest $request) 
    {
        $this->form = new CompteMotDePasseOublieForm();
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $compte = $this->form->save();
                $lien = sfConfig::get('app_base_url') . $this->generateUrl("ac_vin_compte_mot_de_passe_oublie_login", array("login" => $compte->login, "mdp" => str_replace("{OUBLIE}", "", $compte->mot_de_passe)));
                try {
                	
                	
                	$infos = sfConfig::get('app_ac_vin_compte_mot_de_passe_oublie_email');
                    $message = $this->getMailer()->composeAndSend(array($infos['from_email'] => $infos['from_name']), $compte->email, $infos['subject'], $this->getPartial('acVinCompte/acVinCompteMotDePasseOublieEmail', array('compte' => $this->compte, 'lien' => $lien)));
                } catch (Exception $e) {
                    $this->getUser()->setFlash('error', "Problème de configuration : l'email n'a pu être envoyé");
                }
                $this->redirect('@ac_vin_compte_mot_de_passe_oublie_confirm');
            }
        }
    }
    
    public function executeAcVinCompteMotDePasseOublieConfirm(sfWebRequest $request) 
    {
        
    }
}
