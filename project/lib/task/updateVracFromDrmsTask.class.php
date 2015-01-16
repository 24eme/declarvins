<?php

class updateDrmsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'vrac-from-drms';
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
        
        $drms = DRMClient::getInstance()->findAll()->rows;
        $nbDrm = count($drms);
        $i = 0;
        foreach ($drms as $drm) {
        	$i++;
        	$d = DRMClient::getInstance()->find($drm->id);
        	if ($d->isValidee() && $d->referente) {
	        foreach ($d->getDetails() as $detail) {
	            foreach ($detail->vrac as $numero => $vrac) {
	                $volume = $vrac->volume;
	                $contrat = VracClient::getInstance()->findByNumContrat($numero);
	                $enlevements = $contrat->getOrAdd('enlevements');
	                $drm = $enlevements->getOrAdd($d->_id);
	                $drm->add('volume', $volume);
	                $contrat->save(false);
        			$this->logSection('update', $contrat->_id." : succès de la mise à jour des enlevements.");
	            }
	        }
        	}
	        $this->logSection('update', $i."/".$nbDrm." traitées");
        }

    }

}