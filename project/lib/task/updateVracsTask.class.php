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
        $contrats = array(
        		
        );
        $i = 1;
        $nb = count($contrats);
        foreach ($contrats as $contrat) {
			$vrac = VracClient::getInstance()->findByNumContrat($contrat);
			if ($mother = $vrac->getMother()) {
				if ($mother->hasEnlevements()) {
					$enlevementsMother = $mother->getOrAdd('enlevements');
					$enlevements = $vrac->getOrAdd('enlevements');
					$change = false;
					foreach ($enlevementsMother as $drm => $infos) {
						if (!$enlevements->exist($drm)) {
							$e = $enlevements->getOrAdd($drm);
							$e->volume = $infos->volume;
							$change = true;
						} else {
							$e = $enlevements->getOrAdd($drm);
							if ($infos->volume != $e->volume) {
								$d = DRMClient::getInstance()->find($drm);
								$volume = 0;
								foreach ($d->getDetails() as $detail) {
									foreach ($detail->vrac as $numero => $v) {
										if (preg_match('/'.$vrac->numero_contrat.'/i', $numero)) {
											$volume += $v->volume;
										}
									}
								} 
								$e->volume = $volume;
								$change = true;								
							}
						}
					}
					if ($change) {
						$vrac->updateEnlevements();
						$vrac->save();
						$this->logSection("update", $vrac->_id." mis à jour avec succès ($i / $nb)", null, 'SUCCESS');
					} else {
						$this->logSection("update", "($i / $nb)", null, 'SUCCESS');
					}
				}
			}
			$i++;
        }
    }

}