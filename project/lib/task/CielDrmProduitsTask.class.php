<?php

class CielDrmProduitsTask extends sfBaseTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'drm-produits';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    

	$items = CielDrmView::getInstance()->findAllTransmises();
	foreach ($items as $item) {
		if ($drm = DRMClient::getInstance()->find($item->id)) {
			foreach ($drm->getDetails() as $produit) {
				echo $drm->identifiant.";".$drm->declarant->no_accises.";".$drm->periode.";".$produit->interpro.";".$produit->getIdentifiantDouane().";".$produit->getFormattedLibelle("%g% %a% %l% %co% %ce% %la%")."\n";
			}
		}
	}
  }
}
