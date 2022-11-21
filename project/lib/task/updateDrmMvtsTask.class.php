<?php

class updateDrmMvtsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
                // add your own options here
        ));


    	$this->addArguments(array(
            new sfCommandArgument('drmid', sfCommandArgument::OPTIONAL, 'Identifiant DRM'),
    	));

        $this->namespace = 'update';
        $this->name = 'drmMvts';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [update|INFO] task does things.
Call it with:
  [php symfony update|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

        $items = ($arguments['drmid'])? array((object) array('id' => $arguments['drmid'])) : DRMAllView::getInstance()->findAll()->rows;
        foreach ($items as $item) {
        	if ($drm = DRMClient::getInstance()->find($item->id)) {
                foreach($drm->getMouvements() as $mouvements) {
                    foreach($mouvements as $mouvement) {
                        $mouvement->region = EtablissementClient::REGION_CVO;
                        if (!in_array($mouvement->interpro, ['INTERPRO-IR','INTERPRO-CIVP','INTERPRO-IVSE'])) {
                            $mouvement->region = EtablissementClient::REGION_HORS_CVO;
                        }
                        if ($mouvement->facturable && ($mouvement->date < DRMDetail::START_FACTURATION_MVT_AT)) {
                            $mouvement->facture = 1;
                        }
                    }
                }
                $drm->save();
                echo "SUCCESS ".$drm->_id." updated\n";
        	}
        }
    }

}
