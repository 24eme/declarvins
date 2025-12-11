<?php

class CielCftTask extends sfBaseTask
{
	CONST RAPPORT_OK_KEY = 'OK';
	CONST RAPPORT_DIFF_KEY = 'DIFF';
	CONST RAPPORT_NONSAISIE_KEY = 'NONSAISIE';
	CONST RAPPORT_GENERATE_KEY = 'GENERATE';
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
      new sfCommandOption('weekly', null, sfCommandOption::PARAMETER_REQUIRED, 'weekly mode for cron', 0),
      new sfCommandOption('sendVigneronEmailNotification', null, sfCommandOption::PARAMETER_REQUIRED, 'notification par email au vigneron', 0),
      new sfCommandOption('sendInterproEmailRapport', null, sfCommandOption::PARAMETER_REQUIRED, 'rapport d\'integration par email a l\'interpro', 0),
      new sfCommandOption('interpro', null, sfCommandOption::PARAMETER_REQUIRED, 'Interprofession', ''),
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
    $contextInstance = sfContext::createInstance($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $checkingMode = $options['checking'];
    $weeklyMode = $options['weekly'];
    $sendVigneronEmailNotification = ($checkingMode)? false : $options['sendVigneronEmailNotification'];
    $sendInterproEmailRapport = ($checkingMode)? false : $options['sendInterproEmailRapport'];
    $interpro = InterproClient::getInstance()->find($options['interpro']);

    if (!$interpro) {
        echo 'Interpro '.$options['interpro'].' non reconnu';
        return;
    }

    $date = null;
    $target = $arguments['target'];
    if ($weeklyMode) {
    	$date = date('Y-m-d', strtotime('-5 days'));
    	$target .= "&from=".$date;
    }

    $list = simplexml_load_file($target, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    $rapport = $this->initRapport();
    $files = array();
    if ($list !== FALSE) {
    	foreach ($list->children() as $item) {
    	    if ($weeklyMode) {
                $modifiedDate = $this->getModifiedFileDate($item);
    	        if ($modifiedDate && $modifiedDate < $date) {
    	            continue;
    	        }
    	    }
    		$xmlIn = simplexml_load_file($item, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    		if ($xmlIn !== FALSE) {
    			if ($drm = $this->getMasterDRM($xmlIn)) {
    				$drmCiel = $drm->getOrAdd('ciel');
    				$export = new DRMExportCsvEdi($drm);
    				if ($xml = $export->exportEDI('xml', $contextInstance)) {
    					$xmlOut = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    					$compare = new DRMCielCompare($xmlIn, $xmlOut);
    					if (!$compare->hasDiff() && !$drm->ciel->valide) {
							if (!$drm->isVersionnable()) {
								$rectif = $drm->getMaster();
                                if (!$checkingMode)
							      $rectif->delete();
							}
							$drm->ciel->valide = 1;
							$drm->referente = 1;
                            if (!$checkingMode)
							    $drm->save();
                            if ($sendVigneronEmailNotification)
							    Email::getInstance()->cielValide($drm);
							$rapport[self::RAPPORT_OK_KEY][] = 'La DRM '.$drm->_id.' a été validée avec succès';
    					} elseif ($compare->hasDiff() && $drm->isVersionnable() && !$drm->isRectificative()) {
							$drm_rectificative = $drm->generateRectificative(true);
							$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
							$drm_rectificative->add('ciel', $drm->ciel);
							$drm_rectificative->ciel->xml = null;
							$drm_rectificative->ciel->valide = 0;
							$drm_rectificative->ciel->diff = $xmlIn->asXML();
                            if (!$checkingMode)
							    $drm_rectificative->save();
                            if ($sendVigneronEmailNotification && $drm->isRectificative())
							    Email::getInstance()->cielRectificative($drm, $compare->getLitteralDiff(), $interpro);
    						$rapport[self::RAPPORT_DIFF_KEY][] = 'La DRM '.$drm->_id.' doit être rectifiée suite aux rectifications suivantes : '.$this->getDiffHtmlList($compare);
    						$files[] = $item;
    					}
					} else {
						$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible de générer le XML de La DRM '.$drm->_id;
					}
    			} else {
    			    $generate = false;
    			    if ($drmPrecedente = CielDrmView::getInstance()->findByAccisesPeriode($this->getEA($xmlIn), $this->getPrevPeriode($xmlIn))) {
    			        if($drmPrecedente->hasStocksEpuise() && $this->hasStocksEpuise($xmlIn)) {
    			            if ($drmGeneree = $this->genereDRM($drmPrecedente, $xmlIn)) {
                                if (!$checkingMode)
    							    $drmGeneree->save();
                                if ($sendVigneronEmailNotification)
                                    Email::getInstance()->cielValide($drmGeneree);
        			            $generate = true;
                            }
    			        }
    			    }
    			    if ($generate) {
    			        $rapport[self::RAPPORT_GENERATE_KEY][] = 'La DRM '.$drmGeneree->_id.' a été générée avec succès';
    			    } else {
    				    $rapport[self::RAPPORT_NONSAISIE_KEY][] = 'La DRM '.$this->getPeriode($xmlIn).' de l\'établissement '.$this->getEA($xmlIn).' n\'a pas été saisie sur le portail interprofessionnel : <a href="http://cniv.24eme.fr/tools/SEED.php?accise='.$this->getEA($xmlIn).'" target="_blank">Information SEED</a>';
    				    $files[] = $item;
    			    }
    			}
    		} else {
    			$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible d\'interroger la DRM : '.$item;
    		}
    	}
    } else {
		$rapport[self::RAPPORT_ERROR_KEY][] = 'Impossible d\'interroger le service : '.$target;
    }
    if ($checkingMode||!$sendInterproEmailRapport)
    	$this->printRapport($rapport);
    else
		$this->sendRapport($interpro, $rapport, $files);
  }

  private function printRapport($rapport)
  {
      $contenuRapport = $this->messagizeRapport($rapport);
      echo str_replace("</h2>", "\n", str_replace("</h3>", "\n", str_replace("<h2>", "", str_replace("<h3>", "", str_replace("<li>", "\t", str_replace(array("<ul>", "</ul>", "</li>"), "\n", $contenuRapport))))));
  }

  private function sendRapport($interpro, $rapport, $files)
  {
      $contenuRapport = $this->messagizeRapport($rapport);
      $cc = array(sfConfig::get('app_email_to_notification'));
      if ($interpro->identifiant == 'CIVP') {
          $cc[] = $interpro->email_assistance_ciel;
      }
      $message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), $interpro->email_contrat_inscription, "DeclarVins // Rapport CFT ".$interpro->nom, $contenuRapport)->setContentType('text/html');
      $message->setCc($cc);
      if (count($files) > 0) {
          $target = '/tmp/cielxml/'.$interpro.'/';
          $zipname = date('Ymd').'_xml.zip';
          exec('mkdir -p '.$target);
          exec('mkdir -p '.$target.date('Ymd').'/');
          foreach ($files as $file) {
              $split = explode('/', $file);
              exec('wget -q -O '.$target.date('Ymd').'/'.$split[count($split) - 1].' '.$file);
          }
          exec('zip -j -r '.$target.$zipname.' '.$target.date('Ymd').'/');
          $message->attach(Swift_Attachment::fromPath($target.$zipname));
      }
      $this->getMailer()->sendNextImmediately()->send($message);
  }

  private function genereDRM($drmPrecedente, $xmlIn)
  {
      $drm = $drmPrecedente->generateSuivante();
      $drm->constructId();
      if (DRMClient::getInstance()->find($drm->_id))
        return null;
      $drm->validateAutoCiel($xmlIn->asXML());
      $drm->validate();
      return $drm;
  }

  private function hasStocksEpuise($xmlIn)
  {
      $stockEpuiseSuspendus = (string) $xmlIn->{"declaration-recapitulative"}->{"droits-suspendus"}->{"stockEpuise"};
      $stockEpuiseAcquittes = (string) $xmlIn->{"declaration-recapitulative"}->{"droits-acquittes"}->{"stockEpuise"};
      return (strtolower(trim($stockEpuiseSuspendus)) == "true" && strtolower(trim($stockEpuiseAcquittes)) == "true");
  }

  private function getModifiedFileDate($item)
  {
      $headers = get_headers($item->__toString(),1);
      if ($headers && isset($headers['Last-Modified'])) {
          $d = DateTime::createFromFormat("D, d M Y H:i:s O", $headers['Last-Modified']);
          return $d->format('Y-m-d');
      }
  }

  private function getDiffHtmlList($compare)
  {
      $diffs = '<ul>';
      foreach ($compare->getLitteralDiff() as $k => $v) {
          $diffs .= "<li>$k => $v</li>";
      }
      $diffs .= '</ul>';
      return $diffs;
  }

  private function getMasterDRM($xmlIn)
  {
      $ea = (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
      $periode = sprintf("%4d-%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
      if ($drm = CielDrmView::getInstance()->findByAccisesPeriode($ea, $periode)) {
          if (!$drm->isVersionnable()) {
              $master = $drm->getMaster();
              if ($master->isValidee()) {
                  $drm = $master;
              }
          }
      }
      return $drm;
  }

  private function getEA($xmlIn)
  {
      return (string) $xmlIn->{"declaration-recapitulative"}->{"identification-declarant"}->{"numero-agrement"};
  }

  private function getPeriode($xmlIn)
  {
      return sprintf("%4d-%02d", (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"annee"}, (string) $xmlIn->{"declaration-recapitulative"}->{"periode"}->{"mois"});
  }

  private function getPrevPeriode($xmlIn)
  {
      return date('Y-m', strtotime($this->getPeriode($xmlIn).'-01'. ' -1 month'));
  }

  private function initRapport()
  {
  	$rapport = array();
  	$rapport[self::RAPPORT_OK_KEY] = array();
  	$rapport[self::RAPPORT_DIFF_KEY] = array();
  	$rapport[self::RAPPORT_NONSAISIE_KEY] = array();
  	$rapport[self::RAPPORT_GENERATE_KEY] = array();
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
  		case self::RAPPORT_GENERATE_KEY:
  			$title .= ($nb > 1)? ' DRM à stock épuisé ont bien été générées automatiquement ' : ' DRM a bien été générée';
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
  			if (self::RAPPORT_OK_KEY == $rapportKey) {
  			    continue;
  			}
		  	$message .= '<ul>';
		  	foreach ($rapportItem as $item) {
		  		$message .= '<li>'.$item.'</li>';
		  	}
		  	$message .= '</ul>';
  		}
  	}

  	return $message;
  }
}
