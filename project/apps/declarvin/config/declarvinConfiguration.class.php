<?php

class declarvinConfiguration extends sfApplicationConfiguration
{
	protected $ediRouting = null;
	
	public function generateEdiUrl($name, $parameters = array())
	{
		return '/edi.php'.$this->getEdiRouting()->generate($name, $parameters);
	}
	
	public function getEdiRouting()
	{
		if (!$this->ediRouting)
		{
			$this->ediRouting = new sfPatternRouting(new sfEventDispatcher());
	
			$config = new sfRoutingConfigHandler();
			$routes = $config->evaluate(array(sfConfig::get('sf_apps_dir').'/edi/config/routing.yml'));
	
			$this->ediRouting->setRoutes($routes);
		}
	
		return $this->ediRouting;
	}
	
  	public function configure()
  	{
  	}
}
