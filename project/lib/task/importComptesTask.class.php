<?php

class importComptesTask extends sfBaseTask
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
      // add your own options here
    ));

    $this->namespace        = 'import';
    $this->name             = 'comptes';
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
    
    /*
     * COMPTE VIRTUEL ADMIN
     */

    if ($compte = acCouchdbManager::getClient()->retrieveDocumentById('COMPTE-admin-civp')) {
        $compte->delete();
    }
    $compte = new CompteVirtuel();
    $compte->nom = "CIVP";
    $compte->login = 'admin-civp';
    $compte->email = 'mcouderc@provencewines.com';
    $compte->mot_de_passe = "admin";
    $compte->droits->add(null, 'admin');
    $compte->interpro = "INTERPRO-civp";
    $compte->save();
    
    if ($compte = acCouchdbManager::getClient()->retrieveDocumentById('COMPTE-admin-inter-rhone')) {
        $compte->delete();
    }
    $compte = new CompteVirtuel();
    $compte->nom = "Inter-Rhône";
    $compte->login = 'admin-inter-rhone';
    $compte->email = 'beymard@inter-rhone.com';
    $compte->mot_de_passe = "admin";
    $compte->droits->add(null, 'admin');
    $compte->interpro = "INTERPRO-inter-rhone";
    $compte->save();
    
    if ($compte = acCouchdbManager::getClient()->retrieveDocumentById('COMPTE-admin-intervins-sud-est')) {
        $compte->delete();
    }
    $compte = new CompteVirtuel();
    $compte->nom = "Intervins Sud-Est";
    $compte->login = 'admin-intervins-sud-est';
    $compte->email = 'marie.de-monte@intervins-sudest.org';
    $compte->mot_de_passe = "admin";
    $compte->droits->add(null, 'admin');
    $compte->interpro = "INTERPRO-intervins-sud-est";
    $compte->save();
    
    /*
     * FIN COMPTE VIRTUEL ADMIN
     */

    /*$ldap = new Ldap();
    $ldap->connect();
    if ($ldap->exist($compte))
      $ldap->delete($compte);
    $ldap->saveCompte($compte);*/

    if ($compte = acCouchdbManager::getClient()->retrieveDocumentById('COMPTE-autologin')) {
        $compte->delete();
    }

    $compte = new CompteTiers();
    $compte->nom = "Login";
    $compte->prenom = "Auto";
    $compte->login = 'autologin';
    $compte->interpro = array('INTERPRO-inter-rhone' => array('statut' => 'VALIDE'));
    $compte->email = 'autologin@example.org';
    $compte->save();

    if ($e = acCouchdbManager::getClient()->retrieveDocumentById('ETABLISSEMENT-9223700100')) {
        $e->delete();
    }

    $e = new Etablissement();
    $e->cvi = "9223700100";
    $e->email = "test@example.org";
    $e->interpro = 'INTERPRO-inter-rhone';
    $e->identifiant = "9223700100";
    $e->no_accises  = "FR9200000000";
    $e->no_tva_intracommunautaire = "FR9200000000";
    $e->nom = "Garage d'Actualys";
    $e->siege = array("adresse" => "1 rue Garnier", "code_postal" => "92200", "commune" => "Neuilly");
    $e->statut = "ACTIF";
    $e->save();

    $compte->addEtablissement($e);
    $compte->save();
  }
}
