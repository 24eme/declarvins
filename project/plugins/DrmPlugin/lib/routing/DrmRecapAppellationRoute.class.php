<?php

class DrmRecapAppellationRoute extends DrmRecapLabelRoute {

    public function getConfigAppellation() {
        
        return $this->getObject();
    }
    
    public function getDrmAppellation() {
        
        return $this->getDRM()->get($this->getConfigAppellation()->getHash());
    }
    
    public function getConfigLabel() {
        
         return $this->getConfigAppellation()->getLabel();
    }
    
    protected function getObjectForParameters($parameters) {
        $config_label = parent::getObjectForParameters($parameters);
        $drm_appellations = $this->getDRM()->get($config_label->getHash())->appellations;
        
        if ($config_label) {
            if (!array_key_exists('appellation', $parameters)) {
                foreach($config_label->appellations as $config_appellation) {
                    if ($drm_appellations->exist($config_appellation->getKey())) {
                        
                        return $config_appellation;
                    }
                }
            }

            if ($drm_appellations->exist($parameters['appellation'])) {
                
                return $config_label->appellations->get($parameters['appellation']);
            }
        }

        return null;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = parent::doConvertObjectToArray($object->getLabel());
        $parameters['appellation'] = $object->getKey();
        
        return $parameters;
    }

}