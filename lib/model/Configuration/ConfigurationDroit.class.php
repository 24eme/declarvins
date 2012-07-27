<?php
/**
 * Model for ConfigurationDroit
 *
 */

class ConfigurationDroit extends BaseConfigurationDroit 
{
	public function isEmpty()
	{
		return is_null($this->taux);
	}
}