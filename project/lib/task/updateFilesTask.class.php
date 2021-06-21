<?php

class updateFilesTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
	      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
	      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
	      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
	      // add your own options here
	    ));
        $this->namespace = 'update';
        $this->name = 'files';
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

    	$docs = PieceAllView::getInstance()->getAll();
    	$i = 0;
    	$nb = count($docs);
    	foreach ($docs as $doc) {
    		$fichier = FichierClient::getInstance()->find($doc->id);
        $update = false;
        if ($fichier->libelle == 'Systèmes d’Informations Géographique'||$fichier->libelle == 'Cartographie des parcelles') {
          $fichier->libelle = 'Cartographie des parcelles';
          $fichier->papier = 0;
          $fichier->setComplementeLibelle(false);
          $fichier->pieces->get(0)->libelle = 'Cartographie des parcelles';
          $update = true;
        }
    		$i++;
    		if ($update) {
	         $fichier->save();
       		 $this->logSection('update', $fichier->_id." : succès de la mise à jour (".round(($i/$nb)*100,1)."%)");
    		}
    	}

    }

}
