<?php

class importVracTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('csvFile', sfCommandArgument::REQUIRED, 'Fichier contenant le contrat à importer'),
  	));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'declarvin'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'default'),
      new sfCommandOption('checking', null, sfCommandOption::PARAMETER_REQUIRED, 'Cheking mode', 0),
    ));

    $this->namespace        = 'import';
    $this->name             = 'vrac';
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
    $message = '<h2>Monitoring du flux d\'import des Contrats en provenance d\'InterSud</h2>';
    
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
    	if (!preg_match('/^([a-zA-Z0-9]+)_([a-zA-Z0-9]+)_([a-zA-Z0-9]+).csv$/', $f, $m)) {
			continue;
    	}
		$ea = $m[1];
		$siretCvi = $m[2];
		$visa = $m[3];
		
    	$result = array();
    
	    if (!file_exists($file)) {
	    	$result[] = array('ERREUR', 'ACCES', null, "Le fichier $file n'existe pas");
	    } else {    
	    	
	    	try {
	    		$vrac = VracClient::getInstance()->findByNumContrat($visa);
	    		if (!$vrac) {
		    		$vrac = new Vrac();
		    		$vrac->numero_contrat = $visa;
	    		}
		    	$vracCsvEdi = new VracImportCsvEdi($file, $vrac);
		    	$vracCsvEdi->checkCSV();
		    		
		    	if($vracCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
		    		foreach($vracCsvEdi->getCsvDoc()->erreurs as $erreur) {
		    			if ($erreur->num_ligne > 0) {
		    				$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->diagnostic, $erreur->csv_erreur);
		    			} else {
		    				$result[] = array('ERREUR', 'CSV', null, $erreur->diagnostic, $erreur->csv_erreur);
		    			}
		    		}
		    	} else {
		    		$vracCsvEdi->importCsv();
		    		$errors = 0;
		    		if($vracCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
		    			foreach($vracCsvEdi->getCsvDoc()->erreurs as $erreur) {
		    				$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->diagnostic, $erreur->csv_erreur);
		    				$errors++;
		    			}
		    		} else {
		    			$etablissement = $vrac->getVendeurObject();
		    			//$vrac->constructId();
			    		if (!$etablissement->hasDroit(EtablissementDroit::DROIT_VRAC)) {
			    			$result[] = array('ERREUR', 'ACCES', null, "L'établissement ".$etablissement->identifiant." n'est pas autorisé à déclarer des DRMs");
			    			$errors++;
			    		}
			    		/*if ($existant = VracClient::getInstance()->find($vrac->_id)) {
			    			$vrac->volume_enleve = $existant->volume_enleve;
			    		}*/
			    		if (!$errors) {
			    			if (!$checkingMode) {
			    				$vrac->validateEdi();
			    				$vrac->save();
			    			}
			    			$nbSuccess++;
			    			//$result[] = array('SUCCESS', 'CSV', null, 'Le Contrat '.$vrac->_id." pour ".$vrac->vendeur_identifiant.' a été importé avec succès');
			    		}
		    		}
		    	}
		    		
		    } catch(Exception $e) {
		    	$result[] = array('ERREUR', 'CSV', null, $e->getMessage());
		    }
	  	}
	  	$message .= $this->messagizeRapport($result, $ea, $visa);
    }
    $message = '<h3>'.$nbSuccess.' Contrats créés avec success</h3><h3>Erreurs :</h3><ul>'.$message.'</ul>';
  	if ($checkingMode) {
  		echo str_replace("</h2>", "\n", str_replace("</h3>", "\n", str_replace("<h2>", "", str_replace("<h3>", "", str_replace("<li>", "\t", str_replace(array("<ul>", "</ul>", "</li>"), "\n", $message))))));
  	} else {
  		$mail = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), sfConfig::get('app_email_to_notification'), "DeclarVins // Rapport import Contrats InterSud", $message)->setContentType('text/html');
  		$this->getMailer()->sendNextImmediately()->send($mail);
  	}

  }
  
  private function messagizeRapport($rapport, $etablissementIdentifiant, $visa)
  {
	//$message = '<h3>Etablissement '.$etablissementIdentifiant.' / Contrat '.$visa.'</h3>';
  	//$message .= '<ul>';
  	$message = '';
  	foreach ($rapport as $rapportItem) {
  		$message .= '<li>'.implode(' | ', $rapportItem).' // Contrat '.$visa.'</li>';
  	}
  	//$message .= '</ul>';  	 
  	return $message;
  }
}
  