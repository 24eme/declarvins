<?php

class DSNegocePluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
  	$this->dispatcher->connect('routing.load_configuration', array('DSNegoceRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}