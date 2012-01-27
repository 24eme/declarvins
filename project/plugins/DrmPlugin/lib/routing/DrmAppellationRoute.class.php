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
            foreach ($config_certification->appellations as $config_appellation) {
                if ($drm_appellations->exist($config_appellation->getKey())) {
                    return $config_appellation;
                }
            }
            
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