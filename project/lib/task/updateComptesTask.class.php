<?php

class updateComptesTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'app name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'comptes';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        


        $comptes = CompteAllView::getInstance()->findAll()->rows;
        $ldap = new Ldap();
        foreach ($comptes as $compte) {
        	if ($compte->key[CompteAllView::KEY_TYPE] == 'CompteTiers' && $compte->key[CompteAllView::KEY_STATUT] == _Compte::STATUT_INSCRIT) {
        		if ($c = _CompteClient::getInstance()->find($compte->id)) {
        			try {
  					$result = $ldap->saveCompte($c);
        			} catch (Exception $e) {
        				$result = false;
        			}
  					if (!$result) {
  						$this->logSection("update", $compte->id." bug enregistrement LDAP", null, 'ERROR');
  					} else {
  						$this->logSection("update", $compte->id." enregistré avec succès", null, 'SUCCESS');
  					}
        		}
        	}
        }
    }

}