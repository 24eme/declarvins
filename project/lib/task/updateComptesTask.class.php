<?php

class updateComptesTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
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
        
        $comptes = CompteAllView::getInstance()->findAll();
        //$i = 1;
        $ldap = new Ldap();
        foreach ($comptes->rows as $compte) {
        	if (!preg_match('/^([a-z0-9\-\_\@\.]*)$/', $compte->value[CompteAllView::VALUE_LOGIN])) {
        		$c = _CompteClient::getInstance()->find($compte->id);
        		$n = clone $c;
        		$login = KeyInflector::slugify($compte->value[CompteAllView::VALUE_LOGIN]);
        		echo $compte->value[CompteAllView::VALUE_NOM].';'.$compte->value[CompteAllView::VALUE_PRENOM].';'.$compte->value[CompteAllView::VALUE_RAISON_SOCIALE].';'.$compte->value[CompteAllView::VALUE_EMAIL].';'.$compte->value[CompteAllView::VALUE_TELEPHONE].';'.$compte->value[CompteAllView::VALUE_LOGIN].';'.$login;
        		echo "\n";

        		
  				/*$ldap->deleteCompte($c);
                $c->delete();
                $n->login = $login;
                $n->_id = 'COMPTE-'.$login;
                $n->save();
                $ldap->saveCompte($n);*/
        	}
			//$this->logSection('compte', $compte->id.' OK '.$i);
			//$i++;
        }
    }

}