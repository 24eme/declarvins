<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::OPTIONAL, 'Appellations File'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'configuration';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importConfiguration|INFO] task does things.
Call it with:

  [php symfony importConfiguration|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    if ($current = acCouchdbManager::getClient()->retrieveDocumentById('CURRENT')) {
        $current->delete();
    }
    
    $current = new Current();
    $current->campagne = '2011-11';
    $current->save();
    $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION');
    if (isset($arguments['file']) && !empty($arguments['file'])) {
	    if ($configuration) {
	        $configuration->delete();
	    }
	    
	    $configuration = new Configuration();
	    $aop = $configuration->declaration->certifications->add('AOP')->libelle = 'AOP';
	    $igp = $configuration->declaration->certifications->add('IGP')->libelle = 'IGP';
	    $vinsansig = $configuration->declaration->certifications->add('VINSSANSIG')->libelle = "Vins sans IG";
	    foreach (file($arguments['file']) as $a) {
	        $datas = explode(";", $a);
	        $configuration->declaration->certifications->get($datas[0])->appellations->add(str_replace("\n", "", $datas[2]))->libelle = $datas[1];
	    }
    	$configuration->save();
    } elseif ($configuration) {
    	$configuration->label->add('AB', 'Agriculture Biologique');
    	$configuration->label->add('AR', 'Agriculture Raisonnée');
    	$configuration->label->add('BD', 'Biodynamie');
    	$configuration->label->add('AC', 'Agri confiance');
    	$configuration->label->add('TV', 'Terra Vitis');
    	$configuration->label->add('DD', 'Vigneron développement durable');
    	$configuration->label->add('NMP', 'Nutrition Méditérannéenne en Provence');
    	$configuration->label->add('HVE', 'Haute Valeur Environnementale');
    	$configuration->label->add('FU', 'Elevage en fût');
    	$configuration->label->add('DO', 'Domaine');
    	$configuration->label->add('CH', 'Château');
    	$configuration->label->add('CL', 'Clos');
    	$configuration->label->add('CC', 'Cru Classé');
    	$configuration->label->add('BT', 'Mise en bouteille ("conditionné") à la propriété');
    	$configuration->save();
    } else {
    	$this->logSection("configuration", "Appellation file needed", null, 'ERROR');
    }
  }
}
