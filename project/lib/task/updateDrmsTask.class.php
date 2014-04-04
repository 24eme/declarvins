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
        $this->name = 'drms';
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
        
        $drms = DRMClient::getInstance()->findAll();
        $i = 1;
        $nb = count($drms->rows);
        foreach ($drms->rows as $d) {
        	if ($drm = DRMClient::getInstance()->find($d->id, acCouchdbClient::HYDRATE_DOCUMENT)) {
        		if ($drm->isValidee()) {
        			$needSave = false;
        			foreach ($drm->getDetails() as $detail) {
        				if ($detail->cvo->exist('code') && $detail->cvo->code) {
        					if ($drm->droits->exist('cvo') && $drm->droits->cvo->exist($detail->cvo->code)) {
        						if ($detail->cvo->taux != $drm->droits->cvo->get($detail->cvo->code)->taux) {
        							$detail->cvo->taux = $drm->droits->cvo->get($detail->cvo->code)->taux;
        							$needSave = true;
        						}
        					}
        				}
        				if ($detail->douane->exist('code') && $detail->douane->code) {
        					if ($drm->droits->exist('douane') && $drm->droits->douane->exist($detail->douane->code)) {
        						if ($detail->douane->taux != $drm->droits->douane->get($detail->douane->code)->taux) {
        							$detail->douane->taux = $drm->droits->douane->get($detail->douane->code)->taux;
        							$needSave = true;
        						}
        					}
        					
        				}
        			}
        			if ($needSave) {
        				$drm->save();
						$this->logSection('drm', $d->id.' OK '.$i.'/'.$nb);	
        			}
        		}
				$i++;
        	}
        }
    }

}