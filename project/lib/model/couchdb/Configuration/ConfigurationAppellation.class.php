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
    public function getLabel() {
        return $this->getParent()->getParent();
    }
    
}