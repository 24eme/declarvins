<?php

class CielRappelTask extends sfBaseTask
{
	
	const NB_JOUR_RELANCE = 5;
	
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'rappel';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $items = CielDrmView::getInstance()->findAllTransmises();
    foreach ($items as $item) {
    	if (!$item->value[CielDrmView::VALUE_CIELVALIDE]) {
    		if ($drm = DRMClient::getInstance()->find($item->id)) {
    			if (!$drm->hasVersion()) {
    				$drmCiel = $drm->getOrAdd('ciel');
    				$hasRelance = ($drmCiel->exist('date_relance') && $drmCiel->date_relance)? true : false;
    				$today = new DateTime();
    				$horodatage = new DateTime($drmCiel->horodatage_depot);
					$interval = $today->diff($horodatage);
					$ecart = $interval->format('%a');
    				if (!$hasRelance && $ecart >= self::NB_JOUR_RELANCE) {
    					Email::getInstance()->cielRappel($drm);
    					$drm->ciel->add('date_relance', date('c'));
    					$drm->save(false);
    				}
    			}
    		}
    	}
    }
  }
}
