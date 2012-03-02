<?php
/**
 * Model for ConfigurationMillesime
 *
 */

class ConfigurationMillesime extends BaseConfigurationMillesime {

	protected function loadAllData() {
		parent::loadAllData();
		$this->getLibelles();
    }

    public function getCepage() {

    	return $this->getParentNode();
    }

    public function getAppellation() {

    	return $this->getCepage()->getCouleur()->getLieu()->getAppellation();
    }
}