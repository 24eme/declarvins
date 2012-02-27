<?php

class DrmAppellationRoute extends DrmCertificationRoute {

    public function getConfigAppellation() {
        return $this->getObject();
    }

    public function getDrmAppellation() {

        return $this->getDRM()->getOrAdd($this->getConfigAppellation()->getHash());
    }

    public function getConfigCertification() {

        return $this->getConfigAppellation()->getCertification();
    }

    protected function getObjectForParameters($parameters) {
        $config_certification = parent::getObjectForParameters($parameters);
        $drm_appellations = $this->getDRM()->declaration->certifications->get($config_certification->getKey())->appellations;

        if (!array_key_exists('appellation', $parameters)) {
        	$key = $this->getDRM()->produits->get($config_certification->getKey())->getFirst()->getKey();
        	return $config_certification->appellations->get($key);            
        }

        return $config_certification->appellations->get($parameters['appellation']);
    }

    protected function doConvertObjectToArray($object) {
        if ($object->getDefinition()->getHash() == "/declaration/certifications/*/appellations/*") {
            $parameters = parent::doConvertObjectToArray($object->getCertification());
            $parameters['appellation'] = $object->getKey();
        } elseif($object->getDefinition()->getHash() == "/declaration/certifications/*") {
            $parameters = parent::doConvertObjectToArray($object);
        }

        return $parameters;
    }

}