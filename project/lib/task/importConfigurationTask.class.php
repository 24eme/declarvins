<?php

class importConfigurationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('file', sfCommandArgument::REQUIRED, 'Appellations File'),
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
    
    if ($configuration = acCouchdbManager::getClient()->retrieveDocumentById('CONFIGURATION')) {
        $configuration->delete();
    }
    
    $configuration = new Configuration();
    $aop = $configuration->declaration->labels->add('AOP')->libelle = 'AOP';
    $igp = $configuration->declaration->labels->add('IGP')->libelle = 'IGP';
    $vinsansig = $configuration->declaration->labels->add('VINSSANSIG');
    $vinsansig->libelle = 'Vins sans IG';
    $vinsansig->appellations->add("VINSSANSIG")->libelle = "Vins sans IG";
   
    foreach (file($arguments['file']) as $a) {
        $datas = explode(";", $a);
        $configuration->declaration->labels->get($datas[0])->appellations->add(str_replace("\n", "", $datas[2]))->libelle = $datas[1];
    }
    
    $configuration->save();
  }
}
