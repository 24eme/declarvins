<?php

class MessagesPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
      $this->dispatcher->connect('routing.load_configuration', array('MessagesRouting', 'listenToRoutingLoadConfigurationEvent'));
  }
}
