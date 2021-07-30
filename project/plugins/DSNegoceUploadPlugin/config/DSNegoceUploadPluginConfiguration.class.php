<?php

class DSNegoceUploadPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
  	$this->dispatcher->connect('routing.load_configuration', array('DSNegoceUploadRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
