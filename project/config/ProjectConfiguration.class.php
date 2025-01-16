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
		$this->enablePlugins('acExceptionNotifierPlugin');
        $this->enablePlugins('acElasticaPlugin');
        $this->enablePlugins('ConfigurationProduitPlugin');
        $this->enablePlugins('ConfigurationZonePlugin');
        $this->enablePlugins('acVinEdiPlugin');
        $this->enablePlugins('acVinDAEPlugin');
        $this->enablePlugins('acVinFichierPlugin');
        $this->enablePlugins('DSNegoceUploadPlugin');
        $this->enablePlugins('acVinSubventionPlugin');
        $this->enablePlugins('acVinDSPlugin');
        $this->enablePlugins('acVinSocietePlugin');
        $this->enablePlugins('acVinSV12Plugin');
        $this->enablePlugins('acVinGenerationPlugin');
        $this->enablePlugins('acVinFacturePlugin');
        $this->enablePlugins('MandatSepaPlugin');
        $this->enablePlugins('acVinSV12Plugin');
	}

    public static function getAppRouting()
    {
        if (null !== self::$routing) {
            return self::$routing;
        }

        if (!self::hasActive()) {
            throw new sfException('No sfApplicationConfiguration loaded');
        }
        $appConfig = self::getActive();
        $config = sfFactoryConfigHandler::getConfiguration($appConfig->getConfigPaths('config/factories.yml'));
        $params = array_merge($config['routing']['param'], array('load_configuration' => false,
                                                                 'logging'            => false,
                                                                 'context'            => array('host'      => sfConfig::get('app_routing_context_production_host', 'localhost'),
                                                                                               'prefix'    => sfConfig::get('app_prefix', sfConfig::get('sf_no_script_name') ? '' : '/'.$appConfig->getApplication().'_'.$appConfig->getEnvironment().'.php'),
                                                                                               'is_secure' => sfConfig::get('app_routing_context_secure', true))));
        $handler = new sfRoutingConfigHandler();
        $routes = $handler->evaluate($appConfig->getConfigPaths('config/routing.yml'));
        $routeClass = $config['routing']['class'];
        self::$routing = new $routeClass($appConfig->getEventDispatcher(), null, $params);
        self::$routing->setRoutes($routes);

        return self::$routing;
    }
}
