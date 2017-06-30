<?php

class updateDrmsIgpMauresTask extends sfBaseTask {

    protected function configure() {
     $this->addArguments(array(
       new sfCommandArgument('drm', sfCommandArgument::REQUIRED, 'drm identifiant')
     ));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
                // add your own options here
        ));

        $this->namespace = 'update';
        $this->name = 'igp-maures';
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
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $toDelete = array();
        if ($drm = DRMClient::getInstance()->find($arguments['drm'])) {
        	$produits = $drm->getOrAdd('declaration/certifications/IGP/genres/TRANQ/appellations/D83/mentions/DEFAUT/lieux/MAU/couleurs');
        	foreach ($produits as $key => $value) {
        		$item = $drm->getOrAdd('declaration/certifications/IGP/genres/TRANQ/appellations/MAU/mentions/DEFAUT/lieux/DEFAUT/couleurs/'.$key.'/cepages/DEFAUT/details/DEFAUT');
        		foreach ($value->cepages as $k => $cepage) {
        			foreach ($cepage->details as $k => $detail) {
        				$item->total_debut_mois += $detail->total_debut_mois;
        				$item->total_entrees += $detail->total_entrees;
        				$item->total_sorties += $detail->total_sorties;
        				$item->total += $detail->total;
        				$item->acq_total_debut_mois += $detail->acq_total_debut_mois;
        				$item->acq_total_entrees += $detail->acq_total_entrees;
        				$item->acq_total_sorties += $detail->acq_total_sorties;
        				$item->acq_total += $detail->acq_total;
        				foreach ($detail->stocks_debut as $k => $v) {
        					$item->stocks_debut->{$k} += $v;
        				}
        				foreach ($detail->stocks_fin as $k => $v) {
        					$item->stocks_fin->{$k} += $v;
        				}
        				foreach ($detail->entrees as $k => $v) {
        					if ($k == 'crd_details') {
        						continue;
        					}
        					$item->entrees->{$k} += $v;
        				}
        				foreach ($detail->sorties as $k => $v) {
        					$item->sorties->{$k} += $v;
        				}
        				$toDelete[] = $detail->getHash();
        			}
        		}
        	}
        	foreach ($toDelete as $tod) {
        		$drm->remove($tod);
        	}
        	$drm->update();
        	$drm->save();
        	$this->logSection("igp-maures", $arguments['drm']."updatée avec succès", null, 'SUCCESS');
        } else {
        	$this->logSection("igp-maures", $arguments['drm']." n'existe pas", null, 'ERROR');
        }
        
        
    }

}