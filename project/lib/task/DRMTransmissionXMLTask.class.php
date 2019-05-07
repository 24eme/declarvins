<?php
class DRMTransmissionXMLTask extends sfBaseTask
{
  protected function configure()
  {
  	$this->addArguments(array(
      new sfCommandArgument('drmid', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL'),
  	));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));
    $this->namespace        = 'drm';
    $this->name             = 'transmissionCIEL';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
EOF;
  }
  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $contextInstance = sfContext::createInstance($this->configuration);
    try {
      $drm = DRMClient::getInstance()->find($arguments['drmid']);
      if ($drm->ciel->isTransfere()) {
          echo "DRM;".$drm->_id.";Transmisssion ok;\n";
          exit;
      }
      $result = $drm->transferToCiel();
      if ($result)  {
          Email::getInstance()->cielSended($drm);
        echo "DRM;".$drm->_id.";Transmisssion avec succès;\n";
      }else{
        echo "DRM;".$drm->_id.";Erreur de transmission : ";
        echo $drm->ciel->xml;
        echo "\n";
      }
    }catch(sfException $e) {
      echo "DRM;".$drm->_id.";Exception de transmission : ".$e->getMessage().";\n";
    }
  }
}