<?php
/**
 * Model for ConfigurationCouleur
 *
 */

class ConfigurationCouleur extends BaseConfigurationCouleur {
    
    /**
     *
     * @return ConfigurationLieu
     */
    public function getLieu() {
        return $this->getParent()->getParent();
    }

    public function hasCepage() {
    	return (count($this->cepages) > 1 || (count($this->cepages) == 1 && $this->cepages->getFirst()->getKey() != Configuration::DEFAULT_KEY));
    }

    public function hasMillesime() {
    	foreach($this->cepages as $cepage) {
    		if ($cepage->hasMillesime()) {
    			return true;
    		}
    	}

    	return false;
    }
}