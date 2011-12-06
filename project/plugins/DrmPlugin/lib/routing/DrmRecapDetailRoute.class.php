<?php

class DrmRecapDetailRoute extends DrmRecapAppellationRoute {

    public function getDRMDetail() {
        return $this->getObject();
    }
    
    public function getConfigAppellation() {
        
        return $this->getDRMConfiguration()->get($this->getDRMDetail()->getCouleur()->getAppellation()->getHash());
    }
    
    protected function getObjectForParameters($parameters) {
        $config_appellation = parent::getObjectForParameters($parameters);
        
        $details = $this->getDRM()->get($config_appellation->getHash())->couleurs->add($parameters['couleur'])->details;
        
        if ($details->exist($parameters['detail'])) {
            $drm_detail = $details->get($parameters['detail']);
        } else {
            $drm_detail = $details->add("NOUVELLE");
            $drm_detail->getDocument()->synchroniseProduits();
        }
        

        return $drm_detail;
    }

    protected function doConvertObjectToArray($object) {
        $config_label = $this->getDRMConfiguration()->get($object->getCouleur()->getAppellation()->getHash());
        $parameters = parent::doConvertObjectToArray($config_label);
        $parameters['couleur'] = $object->getCouleur()->getKey();
        $parameters['detail'] = $object->getKey();
        
        return $parameters;
    }

}