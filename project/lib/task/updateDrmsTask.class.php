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
        $drms = array(
        			'DRM-C1979-2014-01', 
        			'DRM-C1557-2014-01', 
        			'DRM-C8793-2014-01', 
        			'DRM-C2428-2013-12-M01', 
        			'DRM-C7221-2014-01', 
        			'DRM-C2536-2014-01', 
        			'DRM-C0813-2014-01', 
        			'DRM-C0266-2014-01', 
        			'DRM-C8575-2014-01',
        			'DRM-C0474-2014-01',
        			'DRM-C1418-2014-01',
        			'DRM-C1418--2014-01-M01',
        			'DRM-C7080-2014-01',
        			'DRM-C2898-2014-01',
        			'DRM-C0461-2014-01',
        			'DRM-C1601-2014-01',
        			'DRM-C2832-2014-01',
        			'DRM-C0241-2014-01',
        			'DRM-C7659-2014-01',
        			'DRM-C7000-2014-01'
        );
        foreach ($drms as $drm) {
        	if ($d = DRMClient::getInstance()->find($drm)) {
        		$d->update();
        		$d->save();
        		$this->logSection('drm', $drm." : succès de la mise à jour.");
        	}
        }
    }

}