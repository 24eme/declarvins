<?php

class DrmRecapCertificationRoute extends sfObjectRoute {
    
    public function getConfigCertification() {
         $this->getObject();
    }
    
    public function getDrmCertification() {
         $this->getDRM()->get($this->getConfigCertification()->getHash());
    }
    
    protected function getObjectForParameters($parameters) {
        if (!array_key_exists('certification', $parameters)) {
            return $this->getDRMConfiguration()->declaration->certifications->getFirst();
        }
        
        if ($this->getDRMConfiguration()->declaration->certifications->exist($parameters['certification'])) {
            return $this->getDRMConfiguration()->declaration->certifications->get($parameters['certification']);
        }
        
        return null;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("certification" => $object->getKey());
        
        return $parameters;
    }
    
    protected function getDRMConfiguration() {
        return ConfigurationClient::getCurrent();
    }
    
    protected function getDRM() {
        return sfContext::getInstance()->getUser()->getDrm();
    }

}