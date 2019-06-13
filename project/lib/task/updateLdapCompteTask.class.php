<?php

class updateLdapCompteTask extends sfBaseTask {

    protected function configure() {
      	$this->addArguments(array(
          new sfCommandArgument('compteid', sfCommandArgument::REQUIRED, 'Identifiant du Compte'),
      	));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'ldap-compte';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $compteid = $arguments['compteid'];
        if (!preg_match('/^COMPTE-/', $compteid)) {
            $compteid = 'COMPTE-'.$compteid;
        }
        $compte = acCouchdbManager::getClient()->find($compteid);
        if (!$compte) {
            $ldap = new Ldap();
            $ldap->removeCompte($compteid);
            echo "UPDATE;".$compteid.";removed;;\n";
            exit;
        }
        try {
            $ldap = new Ldap();
            $ldap->saveCompte($compte);
            echo "UPDATE;".$compteid.";success;;\n";
            exit;
        } catch (Exception $e) {
            echo "UPDATE;".$compteid.";failed;".$e->getMessage().";\n";
            exit;
        }
    }

}