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

    /*if ($civp = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-CIVP')) {
        $civp->delete();
    }
    
    $civp = new Interpro();
    $civp->set('_id', 'INTERPRO-CIVP');
    $this->identifiant = 'CIVP';
    $civp->nom = 'CIVP';
    $civp->email_contrat_vrac = 'eco@provencewines.com';
    $civp->email_contrat_inscription = 'eco@provencewines.com';
    $civp->save();
    $this->logSection('interpro', 'CIVP importé');

    if ($ir = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-IR')) {
        $ir->delete();
    }
    
    $ir = new Interpro();
    $ir->set('_id', 'INTERPRO-IR');
    $this->identifiant = 'IR';
    $ir->nom = 'InterRhône';
    $ir->email_contrat_vrac = 'contrats@inter-rhone.com';
    $ir->email_contrat_inscription = 'contrats@inter-rhone.com';
    $ir->save();
    $this->logSection('interpro', 'InterRhone importé');

    if ($ise = acCouchdbManager::getClient()->retrieveDocumentById('INTERPRO-IVSE')) {
        $ise->delete();
    }
    
    $ise = new Interpro();
    $ise->set('_id', 'INTERPRO-IVSE');
    $this->identifiant = 'IVSE';
    $ise->nom = "Intervins Sud-Est";
    $ise->email_contrat_vrac = '';
    $ise->email_contrat_inscription = '';
    $ise->save();
    $this->logSection('interpro', 'Intervins Sud-Est importé');*/
    
    $this->logSection('interpro', 'Tâche obsolete => utiliser la tâche d\'import de la configuration');
  }
}
