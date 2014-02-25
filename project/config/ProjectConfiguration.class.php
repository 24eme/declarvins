<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
	protected static $routing = null;

	public function setup()
	{
		$this->enablePlugins('acCouchdbPlugin');
		$this->enablePlugins('acPhpCasPlugin');
		$this->enablePlugins('acLdapPlugin');
		$this->enablePlugins('acDompdfPlugin');
		$this->enablePlugins('acVinConfigurationPlugin');
		$this->enablePlugins('acVinLibPlugin');
		$this->enablePlugins('acVinEtablissementPlugin');
		$this->enablePlugins('acVinComptePlugin');
		$this->enablePlugins('acVinImportPlugin');
		$this->enablePlugins('acVinDRMPlugin');
		$this->enablePlugins('ExportPlugin');
		$this->enablePlugins('acLessphpPlugin');
		$this->enablePlugins('MessagesPlugin');
		$this->enablePlugins('UserPlugin');
		$this->enablePlugins('VracPlugin');
		$this->enablePlugins('acVinVracPlugin');
		$this->enablePlugins('acVinDouanePlugin');
		$this->enablePlugins('EmailPlugin');
		$this->enablePlugins('acVinDocumentPlugin');
		$this->enablePlugins('acVinAlertePlugin');
		$this->enablePlugins('DAIDSPlugin');
		$this->enablePlugins('StatistiquePlugin');
		$this->enablePlugins('acExceptionNotifierPlugin');
        $this->enablePlugins('acElasticaPlugin');
        $this->enablePlugins('ConfigurationProduitPlugin');
	}
	
	public static function getAppRouting()
	{
		if (null !== self::$routing) {
			return self::$routing;
		}
		if (sfContext::hasInstance() && sfContext::getInstance()->getRouting()) {
			self::$routing = sfContext::getInstance()->getRouting();
		} else {
			if (!self::hasActive()) {
				throw new sfException('No sfApplicationConfiguration loaded');
			}
			$appConfig = self::getActive();
			$config = sfFactoryConfigHandler::getConfiguration($appConfig->getConfigPaths('config/factories.yml'));
			$params = array_merge($config['routing']['param'], array('load_configuration' => false,
          															 'logging'            => false,
          															 'context'            => array('host'      => sfConfig::get('app_routing_context_production_host', 'localhost'),
            																					   'prefix'    => '',
            														 							   'is_secure' => sfConfig::get('app_routing_context_secure', false))));
			$handler = new sfRoutingConfigHandler();
			$routes = $handler->evaluate($appConfig->getConfigPaths('config/routing.yml'));
			$routeClass = $config['routing']['class'];
			self::$routing = new $routeClass($appConfig->getEventDispatcher(), null, $params);
			self::$routing->setRoutes($routes);
		}
		return self::$routing;
	}
}
