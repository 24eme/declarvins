<?php

class VracRoute extends sfObjectRoute implements InterfaceEtablissementRoute {

    const ETABLISSEMENT_IDENTIFIANT_ADMIN = 'admin';

    protected $vrac = null;
    protected $etablissement = null;
    
    protected function getObjectForParameters($parameters) {

        if(isset($parameters['numero_contrat'])) {
            $this->vrac = VracClient::getInstance()->findByNumContrat($parameters['numero_contrat']);
        }
        
        if (!$this->vrac) 
        {
            $this->vrac = new Vrac();
        }

        if ($parameters['identifiant'] != self::ETABLISSEMENT_IDENTIFIANT_ADMIN) {
            $this->etablissement = EtablissementClient::getInstance()->find($parameters['identifiant']);
        } else {
            $this->etablissement = false;
        }

        return $this->vrac;
    }

    protected function convertObjectToArray($object) {
        $etablissement = false;

        if (isset($object['etablissement'])) {
            $etablissement = $object['etablissement'];
        }

        unset($object['etablissement']);

        return array_merge(parent::convertObjectToArray($object), $this->doConvertEtablissementToArray($etablissement));
    }

    protected function doConvertObjectToArray($object) {
        $parameters = array();

        if ($object->numero_contrat) {
            $parameters["numero_contrat"] = $object->numero_contrat;
        }

        return $parameters;
    }

    protected function doConvertEtablissementToArray($etablissement) {
        if (!$etablissement) {

            return array("identifiant" => self::ETABLISSEMENT_IDENTIFIANT_ADMIN);
        }
        
        return array("identifiant" => $etablissement->identifiant);
    }

    public function getVrac() {
        if (is_null($this->vrac)) {
            $this->getObject();
        }    

        return $this->vrac; 
    }

    public function getEtablissement() {
        if (is_null($this->etablissement)) {
            $this->getObject();
        }

        return $this->etablissement;
    }
    
}