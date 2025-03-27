<?php

/**
 * global actions.
 *
 * @package    declarvin
 * @subpackage global
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class globalActions extends sfActions
{
    public function executeSecure() {
        //if ($this->getUser()->hasCredential(myUser::CREDENTIAL_ADMIN))
        //return $this->redirect("@tiers");
    }

    public function executeHeader(sfWebRequest $request) {
        $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)||$this->getRequest()->getParameter('isAdmin', false);
        $interpro = InterproClient::getInstance()->retrieveById($this->getRequest()->getParameter('interpro'));
        $etablissement = EtablissementClient::getInstance()->retrieveById($this->getRequest()->getParameter('identifiant'));
        $header = $this->getPartial("global/header", ['isAdmin' => $isAdmin]);
        if ($etablissement) {
            $header .= $this->getPartial("global/navTop", ['active' => $this->getRequest()->getParameter('nav'), 'configuration' => ConfigurationClient::getCurrent(), 'isAdmin' => $isAdmin, 'etablissement' => $etablissement]);
        } elseif ($isAdmin) {
            $header .= $this->getPartial("global/navBack", ['active' => $this->getRequest()->getParameter('nav'), 'configuration' => ConfigurationClient::getCurrent(), 'isAdmin' => $isAdmin, 'interpro' => $interpro]);
        } else {
            throw new Exception("Header not printable");
        }
        echo $header;exit;
    }

    public function executeFooter(sfWebRequest $request) {
        $footer = $this->getPartial("global/footer");
        echo $footer;exit;
    }
}
