<?php
class SocieteRoute extends sfObjectRoute implements InterfaceSocieteRoute, InterfaceEtablissementRoute {

    protected $societe = null;

    protected function getObjectForParameters($parameters = null) {
      $this->societe = SocieteClient::getInstance()->find($parameters['identifiant']);
      if(!$this->societe) {
        throw new sfError404Exception("Société non trouvé");
      }
      $myUser = sfContext::getInstance()->getUser();

      $tiers = array_keys($myUser->getCompte()->tiers->toArray(true,false));
      $etablissements = array_keys($this->societe->etablissements->toArray(true,false));

      if ($myUser->hasTeledeclaration() && !$myUser->isAdmin() && !array_intersect($tiers, $etablissements)) {

            throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
      }
      if(!$myUser->isAdmin() && !array_intersect($tiers, $etablissements) && $this->getSociete()->type_societe != SocieteClient::TYPE_OPERATEUR) {

          throw new sfError403Exception("Vous n'avez pas le droit d'accéder à cette page");
      }
      $module = sfContext::getInstance()->getRequest()->getParameterHolder()->get('module');
      sfContext::getInstance()->getResponse()->setTitle(strtoupper($module).' - '.$this->societe->raison_sociale);
      return $this->societe;
    }

    protected function doConvertObjectToArray($object = null) {

        return array("identifiant" => $object->getIdentifiant());
    }

    public function getSociete() {
      if (!$this->societe) {
           $this->societe = $this->getObject();
      }
      return $this->societe;
    }

    public function getEtablissement() {

        return $this->getSociete()->getEtablissementPrincipal();
    }
}
