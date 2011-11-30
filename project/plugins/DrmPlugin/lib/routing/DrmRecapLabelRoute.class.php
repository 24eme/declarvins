<?php

class DrmRecapLabelRoute extends sfObjectRoute {
    
    protected function getObjectForParameters($parameters) {
        if (!array_key_exists('label', $parameters)) {
            return ConfigurationClient::getCurrent()->declaration->labels->getFirst();
        }
        
        if (ConfigurationClient::getCurrent()->declaration->labels->exist($parameters['label'])) {
            return ConfigurationClient::getCurrent()->declaration->labels->get($parameters['label']);
        }
        
        return null;
    }

    protected function doConvertObjectToArray($object) {  
        $parameters = array("label" => $object->getKey());
        
        return $parameters;
    }

}