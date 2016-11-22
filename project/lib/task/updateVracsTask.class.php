<?php

class updateVracsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
	      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
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
        // initialize the database connection
    	$databaseManager = new sfDatabaseManager($this->configuration);
    	$connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
    	$contrats = VracSoussigneIdentifiantView::getInstance()->findByEtablissement('CIVP24283')->rows;
    	$i = 0;
    	foreach ($contrats as $contrat) {
    		$date = new DateTime($contrat->value[VracSoussigneIdentifiantView::VRAC_VIEW_DATESAISIE]);
    		if ($contrat->value[VracSoussigneIdentifiantView::VRAC_VIEW_ACHETEUR_ID] == 'CIVP24283' && $date->format('Ymd') >= '20140801') {
    			$c = VracClient::getInstance()->find($contrat->id);
    			$c->type_prix = 'definitif';
    			$c->save(false);
    			$i++;
    			$this->logSection('update', $c->_id." : succès de la mise à jour.");
    		}
    	}
    	$this->logSection('update', $i." contrats traités");

    }

}