<?php

class DrmRecapAppellationRoute extends DrmRecapLabelRoute {

    protected function getObjectForParameters($parameters) {
        $object = parent::getObjectForParameters($parameters);

        if ($object) {
            if (!array_key_exists('appellation', $parameters)) {
                return $object->appellations->getFirst();
            }

            if ($object->appellations->exist($paramters['appellation'])) {
                
                return $object->appellations->get($paramters['appellation']);
            }
        }

        return null;
    }

    protected function doConvertObjectToArray($object) {
        $parameters = parent::doConvertObjectToArray($object);
        $parameters['appellation'] = $object->getKey();
        
        return $parameters;
    }

}