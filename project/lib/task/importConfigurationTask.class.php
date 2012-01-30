<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
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

    $import_dir = sfConfig::get('sf_data_dir').'/import/configuration';

    if ($current = acCouchdbManager::getClient()->retrieveDocumentById('CURRENT')) {
        $current->delete();
    }
    
    $current = new Current();
    $current->campagne = '2011-11';
    $current->save();
    $configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION');
    if ($configuration) {
      $configuration->delete();
    }
	    
    $configuration = new Configuration();
    
    $aop = $configuration->declaration->certifications->add('AOP')->libelle = 'AOP';
    $igp = $configuration->declaration->certifications->add('IGP')->libelle = 'IGP';
    $vinsansig = $configuration->declaration->certifications->add('VINSSANSIG')->libelle = "Vins sans IG";

    foreach (file($import_dir.'/appellations') as $a) {
        $datas = explode(";", $a);
        $appellation = $configuration->declaration->certifications->get($datas[0])->appellations->add(str_replace("\n", "", $datas[2]));

        $appellation->libelle = $datas[1];
        $blanc = $appellation->couleurs->add('blanc');
        $blanc->libelle = "Blanc";
        $blanc->cepages->add('DEFAUT')->millesimes->add('DEFAUT');
        $rouge = $appellation->couleurs->add('rouge');
        $rouge->libelle = "Rouge";
        $rouge->cepages->add('DEFAUT')->millesimes->add('DEFAUT');
        $rose = $appellation->couleurs->add('rose');
        $rose->libelle = "Rosé";
        $rose->cepages->add('DEFAUT')->millesimes->add('DEFAUT');
    }

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
  }
}