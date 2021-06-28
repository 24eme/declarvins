<?php

class generateSocieteByEtablissementTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addArguments(array(
       new sfCommandArgument('identifiant', sfCommandArgument::REQUIRED, 'identifiant etablissement'),
     ));
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'generate';
    $this->name             = 'societe-by-etablissement';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

  	$etablissement = EtablissementClient::getInstance()->find($arguments['identifiant']);

    if (!$etablissement) {
      $this->logSection("generate:societe-by-etablissement", "Etablissement not found with id : ".$arguments['identifiant'], null, 'ERROR');
    }

    $societe = $etablissement->getGenerateSociete();
    $societe->save();

    $this->logSection("debug", "Société créée avec succès ".$societe->_id, null, 'SUCCESS');

  }
}
