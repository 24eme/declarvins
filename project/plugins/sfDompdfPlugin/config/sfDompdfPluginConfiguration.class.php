<?php

class sfDompdfPluginConfiguration extends sfPluginConfiguration
{
  public function initialize()
  {
    sfConfig::set('sf_autoloading_functions', array(array('sfDOMPDFBridge', 'autoload')));
  }
}
