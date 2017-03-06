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
        
    	$contrats = VracAllView::getInstance()->findAll()->rows;
    	$i = 0;
    	$nb = count($contrats);
    	$configuration = ConfigurationClient::getCurrent();
    	foreach ($contrats as $contrat) {
    		$c = VracClient::getInstance()->find($contrat->id);
    		if ($c->produit) {
	        	if ($configurationProduit = $configuration->getConfigurationProduit($c->produit)) {
	        		$c->setDetailProduit($configurationProduit);
	        		$c->save(false);
	        	}
    		}
    		$i++;
    		$this->logSection('update', $c->_id." : succès de la mise à jour (".round(($i/$nb)*100,1)."%)");
    	}

    }

}