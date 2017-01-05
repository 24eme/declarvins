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
		ini_set('memory_limit', '2048M');
  		set_time_limit(0);
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        
        $comptes = CompteAllView::getInstance()->findAll()->rows;
        $i = 0;
        $nb = count($comptes);
        foreach ($comptes as $compte) {
        	$i++;
			if ($compte->value[CompteAllView::VALUE_CIEL]) {
	        	if ($c = _CompteClient::getInstance()->find($compte->id)) {
	        		foreach ($c->tiers as $etab => $values) {
	        			$etablissement = EtablissementClient::getInstance()->find($etab);
	        			$etablissement->transmission_ciel = 1;
	        			$etablissement->save();
	        			$pourc = floor($i / $nb * 100);
	        			$this->logSection("update", $etablissement->id." enregistré avec succès $i / $nb ($pourc)", null, 'SUCCESS');
	        		}
	        	}
			}
        }
    }

}