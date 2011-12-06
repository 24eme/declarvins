<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('acCouchdbPlugin');
    $this->enablePlugins('acPhpCasPlugin');
    $this->enablePlugins('acLdapPlugin');
    $this->enablePlugins('acDompdfPlugin');
    $this->enablePlugins('acVinComptePlugin');
    $this->enablePlugins('DrmPlugin');
    $this->enablePlugins('ExportPlugin');
    $this->enablePlugins('ImportPlugin');
    $this->enablePlugins('sfLESSPlugin');
  }
}
