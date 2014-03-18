<?php

class updateVracsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'vracs';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        ini_set('memory_limit', '512M');
        set_time_limit('3600');
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        
        $vracs = VracClient::getInstance()->findAll();
        $i = 1;
        foreach ($vracs->rows as $v) {
        	$vrac = VracClient::getInstance()->find($v->id);
        	$ars = $vrac->acheteur->raison_sociale;
        	$an = $vrac->acheteur->nom;
        	$vrs = $vrac->vendeur->raison_sociale;
        	$vn = $vrac->vendeur->nom;
        	$mrs = $vrac->mandataire->raison_sociale;
        	$mn = $vrac->mandataire->nom;
        	$vrac->acheteur->raison_sociale = $an;
        	$vrac->acheteur->nom = $ars;
        	$vrac->vendeur->raison_sociale = $vn;
        	$vrac->vendeur->nom = $vrs;
        	$vrac->mandataire->raison_sociale = $mn;
        	$vrac->mandataire->nom = $mrs;
        	$vrac->save(false);
			$this->logSection('vrac', $v->id.' OK '.$i);
			$i++;
        }
    }

}