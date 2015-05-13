<?php

class acCouchdbDocumentSimpleUpdateTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('doc_id', sfCommandArgument::REQUIRED, 'ID du document'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'document';
    $this->name             = 'simple-update';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [documentSimpleUpdate|INFO] task does things.
Call it with:

  [php symfony documentSimpleUpdate|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $doc = acCouchdbManager::getClient()->getDoc($arguments['doc_id']);

    if(!$doc) {
      throw new sfException(sprintf("Document %s introuvable", $arguments['doc_id']));
    }

    acCouchdbManager::getClient()->storeDoc($doc);

    echo sprintf("Document %s updated\n", $doc->_id);
  }
} 