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
	
	public function getBaseConfLibelle()
	{
		if ($this->getDocument()->droits->exist($this->getCode())) {
			return $this->getDocument()->droits->get($this->getCode());
		}
		return $this->getLibelle();
	}
}