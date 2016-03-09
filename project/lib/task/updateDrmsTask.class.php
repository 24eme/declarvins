<?php

class updateDrmsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      		new sfCommandOption('drm', null, sfCommandOption::PARAMETER_REQUIRED, null)
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
        $date  = new DateTime('2006-01-01T00:00:00');
        $endDate = new DateTime('2016-02-02T00:00:00');
        while ($date < $endDate) {
        	$begin = $date->format('c');
        	$date->modify('+1 year');
        	$end = $date->format('c');
	        $drms = DRMDateView::getInstance()->findByInterproAndDates('INTERPRO-CIVP', array('begin' => $begin, 'end' => $end))->rows;
	        foreach ($drms as $drm) {
	        	if (preg_match('/^declaration\/certifications\/AOP\/genres\/TRANQ/', $drm->key[4])) {
	        		$d = DRMClient::getInstance()->find($drm->id);
	        		$d->setDroits();
	        		$d->save();
	        		$this->logSection('update', $d->_id." : succès de la mise à jour des droits.");exit;
	        	}
	        }
        }
    }

}