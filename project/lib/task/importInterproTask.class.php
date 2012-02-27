<?php

class importInterproTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'interpro';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [importInterpo|INFO] task does things.
Call it with:

  [php symfony importInterpo|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    if ($civp = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-civp')) {
        $civp->delete();
    }
    
    $civp = new Interpro();
    $civp->set('_id', 'INTERPRO-civp');
    $civp->nom = 'CIVP';
    $civp->save();

    if ($ir = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-inter-rhone')) {
        $ir->delete();
    }
    
    $ir = new Interpro();
    $ir->set('_id', 'INTERPRO-inter-rhone');
    $ir->nom = 'InterRhône';
    $ir->save();

    if ($ise = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-intervins-sud-est')) {
        $ise->delete();
    }
    
    $ise = new Interpro();
    $ise->set('_id', 'INTERPRO-intervins-sud-est');
    $ise->nom = "Intervins Sud-Est";
    $ise->save();
  }
}
