<?php

class DrmDetailRoute extends DrmAppellationRoute {

    public function getDRMDetail() {
        return $this->getObject();
    }
    
    public function getConfigAppellation() {
        return $this->getDRMConfiguration()->get($this->getDRMDetail()->getAppellation()->getHash());
    }
    
    protected function getObjectForParameters($parameters) {
        $config_appellation = parent::getObjectForParameters($parameters);
        
        $drm_detail = $this->getDrm()->get($config_appellation->getHash())
                                  ->lieux->add($parameters['lieu'])
                                  ->couleurs->add($parameters['couleur'])
                                  ->cepages->add($parameters['cepage'])
                                  ->millesimes->add($parameters['millesime'])
                                  ->details->get($parameters['detail']);

        return $drm_detail;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = parent::doConvertObjectToArray($object->getAppellation());
        $parameters['lieu'] = $object->getLieu()->getKey();
        $parameters['couleur'] = $object->getCouleur()->getKey();
        $parameters['cepage'] = $object->getCepage()->getKey();
        $parameters['millesime'] = $object->getMillesime()->getKey();
        $parameters['detail'] = $object->getKey();
        
        return $parameters;
    }

}