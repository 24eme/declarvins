<?php
/**
 * Model for ConfigurationCouleur
 *
 */

class ConfigurationCouleur extends BaseConfigurationCouleur {
    
    /**
     *
     * @return ConfigurationAppellation
     */
    public function getAppellation() {
        return $this->getParent()->getParent();
    }
}