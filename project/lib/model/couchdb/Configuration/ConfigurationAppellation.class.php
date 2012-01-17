<?php
/**
 * Model for ConfigurationAppellation
 *
 */

class ConfigurationAppellation extends BaseConfigurationAppellation {
    
    /**
     *
     * @return ConfigurationLabel
     */
    public function getCertification() {
        return $this->getParent()->getParent();
    }
    
}