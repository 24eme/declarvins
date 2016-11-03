<?php

class CielCftTask extends sfBaseTask
{
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('target', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('all', null, sfCommandOption::PARAMETER_REQUIRED, 'All time', 0)
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'cft';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    

    $contextInstance = sfContext::createInstance($this->configuration);
    
    $list = simplexml_load_file($arguments['target'], 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $rapport = array();
    if ($list !== FALSE) {
    	foreach ($list->children() as $item) {
    		$xmlIn = simplexml_load_file($item, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    		if ($list !== FALSE) {
    			$ea = (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
    			$periode = sprintf("%4d-%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
    			if ($drm = CielDrmView::getInstance()->findByAccisesPeriode($ea, $periode)) {
    				$drmCiel = $drm->getOrAdd('ciel');
    				if (!$drmCiel->valide) {
    					$export = new DRMExportCsvEdi($drm);
    					if ($xml = $export->exportEDI('xml', $contextInstance)) {
    						$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    						$compare = new DRMCielCompare($xmlIn, $xmlOut);
    						if (!$compare->hasDiff()) {
    							$drm->ciel->valide = 1;
    							$drm->save();
    							$rapport[] = 'OK // La DRM '.$drm->_id.' a été validée avec succès';
    						} else {
    							$exist = false;
    							if ($drm->isVersionnable()) {
    								$drm_rectificative = $drm->generateRectificative(true);
	    							$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
	    							$drm_rectificative->add('ciel', $drm->ciel);
	    							$drm_rectificative->ciel->xml = null;
	    							$drm_rectificative->ciel->diff = $content;
	    							$drm_rectificative->save();
	    							$diffs = '<ul>';
	    							foreach ($compare->getDiff()as $k => $v) {
	    								$diffs .= "<li>$k : $v</li>";
	    							}
	    							$diffs .= '</ul>';
	    							$rapport[] = 'ATT // La DRM '.$drm->_id.' doit être rectifiée suite aux modifications suivantes : '.$diffs;
    							} else {
    								$rapport[] = 'La DRM '.$drm->_id.' à déjà été traitée';
    							}
    						}
    					} else {
    						$rapport[] = 'Oups // Impossible de générer le XML de La DRM '.$drm->_id;
    					}
    					
    				} else {
    					$rapport[] = 'La DRM '.$drm->_id.' à déjà été traitée';
    				}
    			} else {
    				$rapport[] = 'Oups // La DRM '.$periode.' de l\'établissement '.$ea.' n\'a pas été saisie sur le portail interprofessionnel: '.$item;
    			}
    		} else {
    			$rapport[] = 'Oups // Impossible d\'interroger la DRM : '.$item;
    		}
    	}
    } else {
		$rapport[] = 'Oups // Impossible d\'interroger le service : '.$arguments['target'];
    }
    $s = '<ul>';
    foreach ($rapport as $item) {
    	$s .= '<li>'.$item.'</li>';
    }
    $s .= '</ul>';
    $message = $message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), sfConfig::get('app_email_to_notification'), "DeclarVins // Rapport CFT", $s)->setContentType('text/html');
    $this->getMailer()->sendNextImmediately()->send($message);
  }
}
