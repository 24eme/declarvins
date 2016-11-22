<?php

class acVinEdiPluginConfiguration extends sfPluginConfiguration
{
	public function setup()
	{
		if ($this->configuration instanceof sfApplicationConfiguration) {
			$configCache = $this->configuration->getConfigCache();
			$configCache->registerConfigHandler('config/edi.yml', 'sfDefineEnvironmentConfigHandler', array('prefix' => 'edi_'));
			$configCache->checkConfig('config/edi.yml');
		}
	}
  	public function initialize()
  	{
  		if ($this->configuration instanceof sfApplicationConfiguration) {
  			$configCache = $this->configuration->getConfigCache();
  			include($configCache->checkConfig('config/edi.yml'));
  		}
  	}
}
