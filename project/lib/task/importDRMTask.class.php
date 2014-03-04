<?php

class importDRMTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('file', null, sfCommandOption::PARAMETER_REQUIRED, null)
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'DRM';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csv = new CsvFile($options['file']);
    $lignes = $csv->getCsv();
    $drmClient = DRMClient::getInstance();
    $numLigne = 0;
    $produits = array(
    	'declaration/certifications/IGP/genres/TRANQ/appellations/IGP/mentions/DEFAUT/lieux/DEFAUT/couleurs/blanc/cepages/DEFAUT',
    	'declaration/certifications/IGP/genres/TRANQ/appellations/IGP/mentions/DEFAUT/lieux/DEFAUT/couleurs/rose/cepages/DEFAUT',
    	'declaration/certifications/IGP/genres/TRANQ/appellations/IGP/mentions/DEFAUT/lieux/DEFAUT/couleurs/rouge/cepages/DEFAUT',
    	'declaration/certifications/IGP/genres/TRANQ/appellations/OC/mentions/DEFAUT/lieux/DEFAUT/couleurs/rose/cepages/DEFAUT',
    	'declaration/certifications/IGP/genres/TRANQ/appellations/OC/mentions/DEFAUT/lieux/DEFAUT/couleurs/rouge/cepages/DEFAUT',
    	'declaration/certifications/IGP/genres/TRANQ/appellations/CLR/mentions/DEFAUT/lieux/DEFAUT/couleurs/rose/cepages/DEFAUT'
    );
    foreach ($lignes as $ligne) {
    	$numLigne++;
    	$import = new DRMDetailImport($ligne, $drmClient);
    	if (in_array($import->getHashProduit(), $produits)) {
	    	$drm = $import->getDrm();
	    	if ($import->hasErrors()) {
	    		$this->logSection('drm', "echec de l'import du produit ligne $numLigne", null, 'ERROR');
	    		$this->logBlock($import->getLogs(), 'ERROR');
	    	} else {
	    		$drm->save();
	    		$this->logSection('drm', $drm->get('_id')." : succès de l'import du produit ligne $numLigne.");
	    	}
    	} else {
    		$this->logSection('drm', $numLigne." : squeezé");
    	}
    }
  }
}
