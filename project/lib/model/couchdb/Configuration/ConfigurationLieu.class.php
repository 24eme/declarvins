<?php
/**
 * Model for ConfigurationLieu
 *
 */

class ConfigurationLieu extends BaseConfigurationLieu {
	/**
     *
     * @return ConfigurationAppellation
     */
    public function getAppellation() {
        return $this->getParent()->getParent();
    }
}