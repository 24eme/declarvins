<?php

class updateDrmsTask extends sfBaseTask {

    protected function configure() {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default')
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
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $configurationProduit = ConfigurationProduitClient::getInstance()->find("CONFIGURATION-PRODUITS-IS");
        
        echo "InterOc\n";
        $items = DRMDateView::getInstance()->findByInterproAndDate("INTERPRO-IO", "2000-01-01")->rows;
        foreach ($items as $item) {
        	if ($drm = DRMClient::getInstance()->find($item->id)) {
        		if ($drm->exist($item->key[DRMDateView::KEY_DETAIL_HASH])) {
        			$produit = $drm->get($item->key[DRMDateView::KEY_DETAIL_HASH]);
        			$hash = preg_replace('/\/details\/[a-zA-Z0-9]*/', '', $item->key[DRMDateView::KEY_DETAIL_HASH]);
        			if ($configurationProduit->exist($hash)) {
        				$produit->interpro = "INTERPRO-IS";
        				$drm->save();
        			} else {
        				echo "no conf for ".$hash."\n";
        			}
        		} else {
        			echo "no produit for ".$item->key[DRMDateView::KEY_DETAIL_HASH]."\n";
        		}
        	}
        }
        echo "CIVL\n";
        $items = DRMDateView::getInstance()->findByInterproAndDate("INTERPRO-CIVL", "2000-01-01")->rows;
        foreach ($items as $item) {
        	if ($drm = DRMClient::getInstance()->find($item->id)) {
        		if ($drm->exist($item->key[DRMDateView::KEY_DETAIL_HASH])) {
        			$produit = $drm->get($item->key[DRMDateView::KEY_DETAIL_HASH]);
        			$hash = preg_replace('/\/details\/[a-zA-Z0-9]*/', '', $item->key[DRMDateView::KEY_DETAIL_HASH]);
        			if ($configurationProduit->exist($hash)) {
        				$produit->interpro = "INTERPRO-IS";
        				$drm->save();
        			} else {
        				echo "no conf for ".$hash."\n";
        			}
        		} else {
        			echo "no produit for ".$item->key[DRMDateView::KEY_DETAIL_HASH]."\n";
        		}
        	}
        }
    }

}