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
    $this->enablePlugins('acVinVracPlugin');
  }
}
