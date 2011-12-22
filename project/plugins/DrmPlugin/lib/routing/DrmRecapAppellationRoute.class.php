<?php

class DrmRecapAppellationRoute extends DrmRecapLabelRoute {

    public function getConfigAppellation() {

        return $this->getObject();
    }

    public function getDrmAppellation() {

        return $this->getDRM()->getOrAdd($this->getConfigAppellation()->getHash());
    }

    public function getConfigLabel() {

        return $this->getConfigAppellation()->getLabel();
    }

    protected function getObjectForParameters($parameters) {
        $config_label = parent::getObjectForParameters($parameters);
        $drm_appellations = $this->getDRM()->declaration->certifications->add($config_label->getKey())->appellations;

        if ($config_label) {
            if (!array_key_exists('appellation', $parameters)) {
                foreach ($config_label->appellations as $config_appellation) {
                    if ($drm_appellations->exist($config_appellation->getKey())) {

                        return $config_appellation;
                    }
                }
                return $config_label->appellations->add("nop");
            }

            if ($drm_appellations->exist($parameters['appellation'])) {

                return $config_label->appellations->get($parameters['appellation']);
            }
        }

        return null;
    }

    protected function doConvertObjectToArray($object) {
        if ($object->getDefinition()->getHash() == "/declaration/certifications/*/appellations/*") {
            $parameters = parent::doConvertObjectToArray($object->getLabel());
            $parameters['appellation'] = $object->getKey();
        } elseif($object->getDefinition()->getHash() == "/declaration/certifications/*") {
            $parameters = parent::doConvertObjectToArray($object);
        }

        return $parameters;
    }

}