<?php

class DrmDetailRoute extends DrmAppellationRoute {

    public function getDRMDetail() {
        return $this->getObject();
    }
    
    public function getConfigAppellation() {
        return $this->getDRMConfiguration()->get($this->getDRMDetail()->getCouleur()->getAppellation()->getHash());
    }
    
    protected function getObjectForParameters($parameters) {
        $config_appellation = parent::getObjectForParameters($parameters);
        
        $drm_detail = $this->getDRM()->get($config_appellation->getHash())
                                  ->couleurs->add($parameters['couleur'])
                                  ->cepages->add($parameters['cepage'])
                                  ->millesimes->add($parameters['millesime'])
                                  ->details->get($parameters['detail']);

        return $drm_detail;
    }

    protected function doConvertObjectToArray($object) {
        $config_certification = $this->getDRMConfiguration()->get($object->getCepage()->getCouleur()->getAppellation()->getHash());
        $parameters = parent::doConvertObjectToArray($config_certification);
        $parameters['couleur'] = $object->getCepage()->getCouleur()->getKey();
        $parameters['cepage'] = $object->getCepage()->getKey();
        $parameters['millesime'] = $object->getMillesime()->getKey();
        $parameters['detail'] = $object->getKey();
        
        return $parameters;
    }

}