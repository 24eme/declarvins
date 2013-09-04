<?php

class acCouchdbDocumentRollBackTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'ID du document'),
    ));

    $this->addOptions(array(
      new sfCommandOption('revision', null, sfCommandOption::PARAMETER_REQUIRED, 'Révision à récupérer, plusieurs formats acceptés (-N, N, N-rev)'),
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'document';
    $this->name             = 'roll-back';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [maintenanceCompteStatut|INFO] task does things.
Call it with:

  [php symfony maintenanceCompteStatut|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $revision = acCouchdbManager::getClient()->findRevision($arguments['doc_id'], $options['revision']);
    acCouchdbManager::getClient()->rollBack($arguments['doc_id'], $revision);
    echo sprintf("Document %s has been roll back to revision : %s\n", $arguments['doc_id'], $revision);
  }
} 