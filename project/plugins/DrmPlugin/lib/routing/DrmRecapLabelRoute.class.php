<?php

class DrmRecapLabelRoute extends sfObjectRoute {
    
    public function getConfigLabel() {
         $this->getObject();
    }
    
    public function getDrmLabel() {
         $this->getDRM()->get($this->getConfigLabel()->getHash());
    }
    
    protected function getObjectForParameters($parameters) {
        if (!array_key_exists('label', $parameters)) {
            return $this->getDRMConfiguration()->declaration->labels->getFirst();
        }
        
        if ($this->getDRMConfiguration()->declaration->labels->exist($parameters['label'])) {
            return $this->getDRMConfiguration()->declaration->labels->get($parameters['label']);
        }
        
        return null;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("label" => $object->getKey());
        
        return $parameters;
    }
    
    protected function getDRMConfiguration() {
        return ConfigurationClient::getCurrent();
    }
    
    protected function getDRM() {
        return sfContext::getInstance()->getUser()->getDrm();
    }

}