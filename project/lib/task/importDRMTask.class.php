<?php

class importDRMTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('csvFile', sfCommandArgument::REQUIRED, 'Fichier contenant la DRM à importer'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
    ));

    $this->namespace        = 'import';
    $this->name             = 'DRM';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF

EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $csvFile = $arguments['csvFile'];
    $checkingMode = $options['checking'];
    $files = array();
    $message = '<h2>Monitoring du flux d\'import des DRM en provenance d\'InterSud</h2>';
    
    if (is_dir($csvFile)) {
    	if ($dir = @opendir($csvFile)) {
    		while (($f = readdir($dir)) !== false) {
    			if($f != ".." && $f != ".") {
    				$files[] = $csvFile.'/'.$f;
    			}
    		}
    		closedir($dir);
    	}
    } else {
    	$files = array($csvFile);
    }

    $nbSuccess = 0;
    foreach ($files as $file) {
    	$f = explode('/', $file);
    	$f = $f[count($f) - 1];
    	if (!preg_match('/^([a-zA-Z0-9]*)_([a-zA-Z0-9]*)_([a-zA-Z0-9]{6}).csv$/', $f, $m)) {
			continue;
    	}
		$ea = $m[1];
		$siretCvi = $m[2];
		$periode = substr($m[3], 0, -2) . "-" . substr($m[3], -2);

    	$result = array();
    
	    if (!file_exists($file)) {
	    	$result[] = array('ERREUR', 'ACCES', null, "Le fichier $file n'existe pas");
	    } else {    
	    	
	    	try {
		    	$drm = new DRM();
		    	$drm->periode = $periode;
		    	$findEtablissement = $drm->setImportableIdentifiant(null, $ea, $siretCvi);
		    	if (!$findEtablissement) {
		    		$result[] = array('ERREUR', 'CSV', null, "Impossible d'identifier l'établissement $ea $siretCvi");
		    	} else {
			    	$configuration = ConfigurationClient::getCurrent();
			    	$controles = array(
			    			DRMCsvEdi::TYPE_CAVE => array(
			    					DRMCsvEdi::CSV_CAVE_COMPLEMENT_PRODUIT => array_keys($configuration->getLabels())
			    			)
			    	);
			    	$drmCsvEdi = new DRMImportCsvEdi($file, $drm, $controles);
			    	$drmCsvEdi->checkCSV();
			    		
			    	if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
			    		foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
			    			if ($erreur->num_ligne > 0) {
			    				$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->diagnostic, $erreur->csv_erreur);
			    			} else {
			    				$result[] = array('ERREUR', 'CSV', null, $erreur->diagnostic, $erreur->csv_erreur);
			    			}
			    		}
			    	} else {
			    		$drmCsvEdi->importCsv();
			    		$errors = 0;
			    		if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
			    			foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
			    				$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->diagnostic, $erreur->csv_erreur);
			    				$errors++;
			    			}
			    		} else {
			    			$etablissement = $drm->getEtablissementObject();
			    			$drm->constructId();
				    		if (!$etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
				    			$result[] = array('ERREUR', 'ACCES', null, "L'établissement ".$etablissement->identifiant." n'est pas autorisé à déclarer des DRMs");
				    			$errors++;
				    		}
				    		
				    		if (DRMClient::getInstance()->find($drm->_id)) {
				    			$master = $drm->findMaster();
			  					if ($master->mode_de_saisie == DRMClient::MODE_DE_SAISIE_EDI) {
			  						$master = $master->generateRectificative();
			  						$drm->version = $master->version;
			  						$drm->precedente = $master->_id;
			  						$drm->constructId();
			  					} else {
			  						$result[] = array('ERREUR', 'ACCES', null, "La DRM ".$drm->periode." pour ".$drm->identifiant." est déjà existante dans la base DeclarVins");
			  						$errors++;
			  					}
				    		}
				    		if (!$errors) {
				    			$drm->update();
				    			$validation = new DRMValidation($drm);
				    
				    			if (!$validation->isValide()) {
				    				foreach ($validation->getErrors() as $error) {
				    					$result[] = array('ERREUR', 'CSV', null, str_replace('Erreur, ', '', $error));
				    				}
				    			} else {
				    				if (!$checkingMode) {
				    					$drm->validate();
				    					$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_EDI;
				    					$drm->save();
				    				}
			    					$nbSuccess++;
				    				//$result[] = array('SUCCESS', 'CSV', null, 'La DRM '.$drm->periode." pour ".$drm->identifiant.' a été importée avec succès');
				    			}
				    		}
			    		}
			    	}
	    		}
		    		
		    } catch(Exception $e) {
		    	$result[] = array('ERREUR', 'CSV', null, $e->getMessage());
		    }
	  	}
	  	$message .= $this->messagizeRapport($result, $ea, $periode);
    }
    $message = '<h3>'.$nbSuccess.' DRM créées avec success</h3><h3>Erreurs :</h3><ul>'.$message.'</ul>';
  	if ($checkingMode) {
  		echo str_replace("</h2>", "\n", str_replace("</h3>", "\n", str_replace("<h2>", "", str_replace("<h3>", "", str_replace("<li>", "\t", str_replace(array("<ul>", "</ul>", "</li>"), "\n", $message))))));
  	} else {
  		$mail = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), sfConfig::get('app_email_to_notification'), "DeclarVins // Rapport import DRM InterSud", $message)->setContentType('text/html');
  		$this->getMailer()->sendNextImmediately()->send($mail);
  	}

  }
  
  private function messagizeRapport($rapport, $etablissementIdentifiant, $periode)
  {
	//$message = '<h3>Etablissement '.$etablissementIdentifiant.' / Periode '.$periode.'</h3>';
  	//$message .= '<ul>';
  	$message = '';
  	foreach ($rapport as $rapportItem) {
  		$message .= '<li>'.implode(' | ', $rapportItem).' // Période '.$periode.'</li>';
  	}
  	//$message .= '</ul>';  	 
  	return $message;
  }
}
