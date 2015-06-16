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
        
        $vracs = VracSoussigneIdentifiantView::getInstance()->findByEtablissement('CIVP24223')->rows;
        $i = 1;
        foreach ($vracs as $values) {
        	$vrac = VracClient::getInstance()->find($values->id);
        	if ($vrac->acheteur->siret == '452421514000012') {
        		$vrac->acheteur->siret = '45242151400012';
        	}
        	if ($vrac->mandataire->siret == '452421514000012') {
        		$vrac->mandataire->siret = '45242151400012';
        	}
        	if ($vrac->vendeur->siret == '452421514000012') {
        		$vrac->vendeur->siret = '45242151400012';
        	}
        	$vrac->save(false);
			$this->logSection('vrac', $values->id.' OK '.$i);
			$i++;
        }
    }

}