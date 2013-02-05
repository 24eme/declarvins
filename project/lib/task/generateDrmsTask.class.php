<?php

class generateDrmsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
                // add your own options here
        ));

        $this->namespace = 'generate';
        $this->name = 'drms';
        $this->briefDescription = 'Génère les drms suivantes de drm au stock null';
        $this->detailedDescription = <<<EOF
The [alertes|INFO] task does things.
Call it with:
  Génère les drms suivantes de drm au stock null
  [php symfony alertes|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {
        ini_set('memory_limit', '512M');
        set_time_limit('3600');
        // initialize the database connection
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $campagne = null;
        
        $etablissements = AlertesEtablissementsView::getInstance()->findActive();
        $now = date('Ym');
        foreach ($etablissements->rows as $etablissement) {
			$identifiant = $etablissement->key[AlertesEtablissementsView::KEY_IDENTIFIANT];
			$drms = AlertesDrmsView::getInstance()->findAllByEtablissement($identifiant);
			$drmsTab = array();
			$start = null;
			foreach ($drms->rows as $drm) {
				$date = $drm->key[AlertesDrmsView::KEY_CAMPAGNE_ANNEE].$drm->key[AlertesDrmsView::KEY_CAMPAGNE_MOIS];
				if (!$start || $start > $date) {
					$start = $date;
				}
				$drmsTab[$date] = $drm;
			}
			echo "\n".$identifiant." : \n";
			print_r($drmsTab);
			if (count($drmsTab) > 0) {
				ksort($drmsTab);
				$end = date('Ym');
				while ($start <= $end) {
					//echo $start." != ".$end."\n";
					$start = $this->getNextMonth($start);
				}
			}
        }
    }
    
    private function getNextMonth($date) {
		$year = substr($date, 0, 4);
		$month = substr($date, -2);
		return ($month != 12)? sprintf('%04d', $year).sprintf('%02d', $month + 1) : sprintf('%04d', $year + 1).sprintf('%02d', 1);
    }

}