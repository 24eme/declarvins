<?php

class ConfigurationZonePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('ConfigurationZoneRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
