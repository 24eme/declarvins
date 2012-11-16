<?php

class DAIDSPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('DAIDSRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
