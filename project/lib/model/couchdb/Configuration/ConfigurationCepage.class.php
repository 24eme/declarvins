<?php
/**
 * Model for ConfigurationCepage
 *
 */

class ConfigurationCepage extends BaseConfigurationCepage {
	
	public function hasMillesime() {
    	return (count($this->millesimes) > 1 || (count($this->millesimes) == 1 && $this->millesimes->getFirst()->getKey() != Configuration::DEFAULT_KEY));
    }

    public function getCouleur() {
    	return $this->getParentNode();
    }
}