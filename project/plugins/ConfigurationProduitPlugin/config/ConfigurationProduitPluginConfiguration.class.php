<?php

class ConfigurationProduitPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('ConfigurationProduitRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
