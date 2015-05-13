<?php

class acVinDouanePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('DouaneRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
