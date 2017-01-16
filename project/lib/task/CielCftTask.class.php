<?php

class CielCftTask extends sfBaseTask
{
	CONST RAPPORT_OK_KEY = 'OK';
	CONST RAPPORT_DIFF_KEY = 'DIFF';
	CONST RAPPORT_NONSAISIE_KEY = 'NONSAISIE';
	CONST RAPPORT_PASS_KEY = 'PASS';
	CONST RAPPORT_ERROR_KEY = 'ERROR';
	
  protected function configure()
  {

  	$this->addArguments(array(
      new sfCommandArgument('target', sfCommandArgument::REQUIRED, 'Cible contenant les DRM en retour de CIEL'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
      new sfCommandOption('daily', null, sfCommandOption::PARAMETER_REQUIRED, 'Daily mode for cron', 0),
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'Interprofession', ''),
    ));

    $this->namespace        = 'ciel';
    $this->name             = 'cft';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }
    
  private function initRapport()
  {
  	$rapport = array();
  	$rapport[self::RAPPORT_OK_KEY] = array();
  	$rapport[self::RAPPORT_DIFF_KEY] = array();
  	$rapport[self::RAPPORT_NONSAISIE_KEY] = array();
  	$rapport[self::RAPPORT_PASS_KEY] = array();
  	$rapport[self::RAPPORT_ERROR_KEY] = array();
  	return $rapport;
  }
  
  private function getTitle($key, $nb)
  {
  	$title = $nb;
  	switch ($key) {
  		case self::RAPPORT_OK_KEY:
  			$title .= ($nb > 1)? ' DRM ont bien été réintégrées' : ' DRM a bien été réintégrée';
  			break;
  		case self::RAPPORT_DIFF_KEY:
  			$title .= ($nb > 1)? ' DRM présentent des différences et nécessitent une rectification' : ' DRM présente des différences et nécessite une rectification';
  			break;
  		case self::RAPPORT_NONSAISIE_KEY:
  			$title .= ($nb > 1)? ' DRM n\'ont pas été saisies sur la plateforme interprofessionnelle' : ' DRM n\'a pas été saisie sur la plateforme interprofessionnelle';
  			break;
  		case self::RAPPORT_PASS_KEY:
  			$title .= ($nb > 1)? ' DRM déjà traitées' : ' DRM déjà traitée';
  			break;
  		case self::RAPPORT_ERROR_KEY:
  			$title .= ($nb > 1)? ' Erreurs sont survenues' : ' Erreur est survenue';
  			break;
  		default:
  			$title = '';
  	}
  	return $title;
  }
  
  private function messagizeRapport($rapport)
  {
  	$message = '<h2>Monitoring du flux de DRM en provenance de Prodou@ne / CIEL</h2>';
  	
  	foreach ($rapport as $rapportKey => $rapportItem) {
  		if (count($rapportItem) > 0 && $rapportKey != self::RAPPORT_PASS_KEY) {
  			$message .= '<h3>'.$this->getTitle($rapportKey, count($rapportItem)).'</h3>';
		  	$message .= '<ul>';
		  	foreach ($rapportItem as $item) {
		  		$message .= '<li>'.$item.'</li>';
		  	}
		  	$message .= '</ul>';
  		}
  	}
  	
  	return $message;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $checkingMode = $options['checking'];
    $dailyMode = $options['daily'];
    $interpro = $options['interpro'];
    if ($interpro) {
    	$interpro = InterproClient::getInstance()->find($interpro);
    }
    $contextInstance = sfContext::createInstance($this->configuration);
    
    if ($dailyMode) {
    	$date = new DateTime();
    	$date->modify('-1 day');
    	$target = $arguments['target']."&from=".$date->format('Y-m-d');	
    } else {
    	$target = $arguments['target'];	
    }
    
    $list = simplexml_load_file($target, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $rapport = $this->initRapport();
    $files = array();
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
    							if (!$checkingMode) {
	    							$drm->ciel->valide = 1;
	    							$drm->save();
    							}
    							$rapport[self::RAPPORT_OK_KEY][] = 'La DRM '.$drm->_id.' a été validée avec succès';
    						} else {
    							$exist = false;
    							if ($drm->isVersionnable()) {
    								if (!$checkingMode) {
	    								$drm_rectificative = $drm->generateRectificative(true);
		    							$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
		    							$drm_rectificative->add('ciel', $drm->ciel);
		    							$drm_rectificative->ciel->xml = null;
		    							$drm_rectificative->ciel->diff = $xmlIn->asXML();
		    							$drm_rectificative->save();
    								}
	    							$diffs = '<ul>';
	    							foreach ($compare->getDiff()as $k => $v) {
	    								$diffs .= "<li>$k : $v</li>";
	    							}
	    							$diffs .= '</ul>';
	    							$rapport[self::RAPPORT_DIFF_KEY][] = 'La DRM '.$drm->_id.' ('.$ea.') doit être rectifiée suite aux modifications suivantes : '.$diffs;
	    							$files[] = $item;
    							} else {
    								$rapport[self::RAPPORT_PASS_KEY][] = 'La DRM '.$drm->_id.' à déjà été traitée';
    							}
    						}
    					} else {
    						$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible de générer le XML de La DRM '.$drm->_id;
    					}
    					
    				} else {
    					$rapport[self::RAPPORT_PASS_KEY][] = 'La DRM '.$drm->_id.' à déjà été traitée';
    				}
    			} else {
    				$rapport[self::RAPPORT_NONSAISIE_KEY][] = 'La DRM '.$periode.' de l\'établissement '.$ea.' n\'a pas été saisie sur le portail interprofessionnel';
    				$files[] = $item;
    			}
    		} else {
    			$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible d\'interroger la DRM : '.$item;
    		}
    	}
    } else {
		$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible d\'interroger le service : '.$target;
    }
    $s = $this->messagizeRapport($rapport);
    if ($checkingMode) {
    	echo str_replace("</h2>", "\n", str_replace("</h3>", "\n", str_replace("<h2>", "", str_replace("<h3>", "", str_replace("<li>", "\t", str_replace(array("<ul>", "</ul>", "</li>"), "\n", $s))))));
    } else {
    	if ($interpro) {
    		$message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), $interpro->email_contrat_inscription, "DeclarVins // Rapport CFT ".$interpro->nom, $s)->setContentType('text/html');
    		$message->setCc(sfConfig::get('app_email_to_notification'));
    	} else {
	    	$message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), sfConfig::get('app_email_to_notification'), "DeclarVins // Rapport CFT", $s)->setContentType('text/html');
    	}
	    if (count($files) > 0) {
	    	foreach ($files as $file) {
	    		$message->attach(Swift_Attachment::fromPath($file));
	    	}
	    }
	    $this->getMailer()->sendNextImmediately()->send($message);
    }
  }
}
