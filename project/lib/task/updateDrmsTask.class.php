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
        
        $rows = acCouchdbManager::getClient()
              ->getView("update", "drm_labels")
              ->rows;
        $i = 0;
        $nb = count($rows);
        $correspondance = array(
        		'AB' => 'BIOL',
        		'ABC' => 'BIOC',
        		'AR' => 'RAIS',
        		'BD' => 'BIOD',
        );
        $libelles = array(
        		'BIOL' => 'Agriculture Biologique',
        		'BIOC' => 'AB en conversion',
        		'RAIS' => 'Agriculture Raisonnée',
        		'BIOD' => 'Biodynamie',
        );
        foreach($rows as $row) {
        	if ($drm = DRMClient::getInstance()->find($row->id)) {
        		$detail = $drm->get($row->key[DRMDateView::KEY_DETAIL_HASH]);
        		if (isset($correspondance[$detail->getKey()])) {
        			$new = $correspondance[$detail->getKey()];
        			$details = $detail->getParent();
        			$detail->labels = array($new);
        			$detail->libelles_label = array($new => $libelles[$new]);
        			$details->add($new, $detail);
        			$detail->delete();
      				$i++;
      				$this->logSection("debug", $drm->_id." : ".$i." / ".$nb." (".round(($i / $nb) * 100)."%) drm(s) updatée(s) avec succès", null, 'SUCCESS');
      				exit;
        		}
        	}
        }
    }

}