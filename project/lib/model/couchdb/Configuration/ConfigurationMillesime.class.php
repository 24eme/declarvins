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
}