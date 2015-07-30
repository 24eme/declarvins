<?php

/**
 * import actions.
 *
 * @package    declarvin
 * @subpackage import
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ediActions extends sfActions
{
	
  protected function getCompte()
  {
  	return acCouchdbManager::getClient('_Compte')->retrieveByLogin($_SERVER['PHP_AUTH_USER']);
  }
	
  protected function securizeInterpro($interpro)
  {
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
  	$interpros = array_keys($this->getCompte()->interpro->toArray());
  	if (!in_array($interpro, $interpros)) {
  		throw new error401Exception("Accès restreint");
  	}
  }
	
  protected function securizeEtablissement($etablissement)
  {
    if (!preg_match('/^ETABLISSEMENT-/', $etablissement)) {
		$etablissement = 'ETABLISSEMENT-'.$etablissement;
    }
    if ($this->getCompte()->exist('tiers')) {
  		$etablissements = array_keys($this->getCompte()->tiers->toArray());
	  	if (!in_array($etablissement, $etablissements)) {
	  		throw new error401Exception("Accès restreint");
	  	}
    } else {
    	if ($etab = EtablissementClient::getInstance()->find($etablissement)) {
    		$this->securizeInterpro($etab->interpro);
    	} else {
    		throw new error401Exception("Accès restreint");
    	}
    }
  }

  public function executeStreamDAIDS(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $daids = DAIDSDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'));
    return $this->renderCsv($daids->rows, DAIDSDateView::VALUE_DATEDESAISIE, "DAIDS", $dateTime->format('c'), $interpro, array(DAIDSDateView::VALUE_IDENTIFIANT_DECLARANT));
  }
  
  public function executeStreamVrac(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $vracs = $this->vracCallback($interpro, VracDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'))->rows);
    return $this->renderCsv($vracs, VracDateView::VALUE_DATE_SAISIE, "VRAC", $dateTime->format('c'), $interpro, array(VracDateView::VALUE_ACHETEUR_ID, VracDateView::VALUE_VENDEUR_ID, VracDateView::VALUE_MANDATAIRE_ID));
  }
  
  public function executeStreamDRM(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut');
    $interpro = $request->getParameter('interpro');
  	$this->securizeInterpro($interpro);
    if (!$date) {
		return $this->renderText("Pas de date définie");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $dateTime = new DateTime($date);
    $dateForView = new DateTime($date);
    $drms = $this->drmCallback(DRMDateView::getInstance()->findByInterproAndDate($interpro, $dateForView->modify('-1 second')->format('c'), true)->rows);
    return $this->renderCsv($drms, DRMDateView::VALUE_DATEDESAISIE, "DRM", $dateTime->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
  }
  
  public function executeStreamCampagneDRM(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!preg_match('/^([0-9]+)-([0-9]+)$/', $campagne, $annees)) {
    	return $this->renderText("Campagne non valide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $campagneManager = new CampagneManager('08-01');
    $datedebut = new DateTime($campagneManager->getDateDebutByCampagne($campagne));
    $datefin = new DateTime($campagneManager->getDateFinByCampagne($campagne));
    $dateForViewDebut = new DateTime($campagneManager->getDateDebutByCampagne($campagne));
    $dateForViewfin = new DateTime($campagneManager->getDateFinByCampagne($campagne));
    $drms = DRMDateView::getInstance()->findByInterproAndDates($interpro, array('begin' => $dateForViewDebut->modify('-1 second')->format('c'), 'end' => $dateForViewfin->modify('+1 day')->modify('-1 second')->format('c')), true);
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
  }
  
  public function executeStreamAnneeDRM(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $annee = $request->getParameter('annee');
    $interpro = $request->getParameter('interpro');
  	$this->securizeInterpro($interpro);
    if (!preg_match('/^([0-9]{4})$/', $annee, $annees)) {
    	return $this->renderText("Année non valide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
    $datedebut = new DateTime($annee.'-01-01');
    $datefin = new DateTime($annee.'-01-01');
    $datefin->modify("+1 year")->modify("-1 day");
    $dateForViewDebut = new DateTime($annee.'-01-01');
    $dateForViewfin = new DateTime($annee.'-01-01');
    $dateForViewfin->modify("+1 year")->modify("-1 day");
    $drms = DRMDateView::getInstance()->findByInterproAndDates($interpro, array('begin' => $dateForViewDebut->modify('-1 second')->format('c'), 'end' => $dateForViewfin->modify('+1 second')->format('c')), true);
    return $this->renderCsv($drms->rows, DRMDateView::VALUE_DATEDESAISIE, "DRM", $datedebut->format('c'), $interpro, array(DRMDateView::VALUE_IDENTIFIANT_DECLARANT));
  }
  
  public function executeStreamDRMEtablissement(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut', null);
    $dateForView = null;
    if ($date) {
    	$dateTime = new DateTime($date);
    	$date = $dateTime->format('c');
    	$dateForView = new DateTime($date);
    	$dateForView->modify('-1 second')->format('c');
    }
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etab = EtablissementClient::getInstance()->find($etablissement);
    $drms = DRMEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView);
    return $this->renderCsv($drms->rows, DRMEtablissementView::VALUE_DATEDESAISIE, "DRM", $date, $etab->interpro);
  }
  
  public function executePushDRMEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);  	
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
	$formUploadCsv = new UploadCSVForm();
    $result = array();
	if ($request->isMethod('post')) {
    	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
      	if ($formUploadCsv->isValid()) {
			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
	      	$lignes = $csv->getCsv();
		    $drmClient = DRMClient::getInstance();
		    $numLigne = 0;
		  	foreach ($lignes as $ligne) {
		    	$numLigne++;
		    	$import = new DRMDetailImport($ligne, $drmClient);
		    	$drm = $import->getDrm();
		    	if ($import->hasErrors()) {
		    		$result[$numLigne] = array('ERREUR', 'LIGNE', $numLigne, implode(' - ', $import->getLogs()));
		    	} else {
		    		$result[$numLigne] = array('OK', '', $numLigne, '');
		    		$drm->save();
		    	}
		    }
      	} else {
      		$result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
      	}
      	
    } else {
    	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    }
    return $this->renderSimpleCsv($result, "drm");
  }
  
  public function executeStreamVracEtablissement(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $date = $request->getParameter('datedebut', null);
    $dateForView = null;
    if ($date) {
    	$dateTime = new DateTime($date);
    	$date = $dateTime->format('c');
    	$dateForView = new DateTime($date);
    	$dateForView->modify('-1 second')->format('c');
    }
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etablissementObject = EtablissementClient::getInstance()->find($etablissement);
    $vracs = $this->vracCallback($etablissementObject->interpro, VracEtablissementView::getInstance()->findByEtablissement($etablissement, $dateForView)->rows);
    return $this->renderCsv($vracs, VracEtablissementView::VALUE_DATE_SAISIE, "VRAC", $date, $etablissementObject->interpro);
  }
  
  public function executePushVracEtablissement(sfWebRequest $request)
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);  	
    $etablissement = $request->getParameter('etablissement');
    $this->securizeEtablissement($etablissement);
    $etablissementObject = EtablissementClient::getInstance()->find($etablissement);
    $interpro = ($etablissementObject)? $etablissementObject->interpo : null;
	$formUploadCsv = new UploadCSVForm();
    $result = array();
	if ($request->isMethod('post')) {
    	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
      	if ($formUploadCsv->isValid()) {
			$csv = new CsvFile(sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5());
	      	$lignes = $csv->getCsv();
    		$vracClient = VracClient::getInstance();
    		$vracConfiguration = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
		    $numLigne = 0;
		  	foreach ($lignes as $ligne) {
		    	$numLigne++;
		    	$import = new VracDetailImport($ligne, $vracClient, $vracConfiguration);
		    	$vrac = $import->getVrac();
		    	if ($import->hasErrors()) {
		    		$result[$numLigne] = array('ERREUR', 'LIGNE', $numLigne, implode(' - ', $import->getLogs()));
		    	} else {
		    		$result[$numLigne] = array('OK', '', $numLigne, '');
		    		$vrac->save();
		    	}
		    }
      	} else {
      		$result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
      	}
      	
    } else {
    	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    }
    return $this->renderSimpleCsv($result, "vrac");
  }
  
  public function executeStreamStatistiquesBilanDrm(sfWebRequest $request) 
  {
  	ini_set('memory_limit', '2048M');
  	set_time_limit(0);
    $campagne = $request->getParameter('campagne');
    $interpro = $request->getParameter('interpro');
    $this->securizeInterpro($interpro);
    if (!$campagne) {
		return $this->renderText("Pas de campagne définie");
    }
    if (!preg_match("/[0-9]{4}-[0-9]{4}/", $campagne)) {
    	return $this->renderText("Campagne invalide");
    }
    if (!preg_match('/^INTERPRO-/', $interpro)) {
		$interpro = 'INTERPRO-'.$interpro;
    }
  	$statistiquesBilan = new StatistiquesBilan($interpro, $campagne);

        $csv_file = 'Identifiant;Raison Sociale;Nom Com.;Siret;Cvi;Num. Accises;Adresse;Code postal;Commune;Pays;Email;Tel.;Fax;Douane;Statut;';
        foreach ($statistiquesBilan->getPeriodes() as $periode) {
            $csv_file .= "$periode;";
        }
        $csv_file .= "\n";

        foreach ($statistiquesBilan->getBilans() as $bilanOperateur) {
            $csv_file .= $statistiquesBilan->getEtablissementFieldCsv($bilanOperateur);
            $csv_file .= $statistiquesBilan->getStatutsDrmsCsv($bilanOperateur);
            $csv_file .= "\n";
        }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$campagne.".csv");
    return $this->renderText($csv_file);
  }
  
  public function executeViewDRM(sfWebRequest $request) 
  {
    $this->securizeEtablissement($request->getParameter('identifiant'));
    $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriodeAndRectificative(
    		$request->getParameter('identifiant'), 
        	DRMClient::getInstance()->buildPeriode($request->getParameter('annee'), $request->getParameter('mois')), 
        	$request->getParameter('rectificative')
    );
    $this->forward404Unless($drm);
    $csv_file = '';
	$csv = DRMCsvFile::createFromDRM($drm);
  	foreach($csv->getCsv() as $line) {
  		$csv_file .= implode(';', $line)."\n";
	}
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$drm->_id.".csv");
    return $this->renderText($csv_file);
  }
  
	public function executePushGrc(sfWebRequest $request)
	{
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
    	$interpro = $request->getParameter('interpro');
    	$this->securizeInterpro($interpro);
    	if (!preg_match('/^INTERPRO-/', $interpro)) {
        	$interpro = 'INTERPRO-'.$interpro;
    	}   
        $interpro = InterproClient::getInstance()->getById($interpro);
        $formUploadCsv = new UploadCSVForm();
    	$result = array();
        if ($request->isMethod('post')) {
	        $formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
	        if ($formUploadCsv->isValid()) {
	        	$file = sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5();
	            $interpro->storeAttachment($file, 'text/csv', 'etablissements.csv');
	            $import = new ImportEtablissementsCsv($interpro);
	            try {
	            	$nb = $import->updateOrCreate();
	                $result[0] = array('OK', '', 0, $nb.' établissement(s) importé(s)');
	            } catch (Exception $e) {
	            	foreach ($import->getErrors() as $k => $v) {
	                	$result[$k] = array('ERREUR', 'LIGNE', $k, implode(' - ', $v));
	                }
	            }
	        } else {
	                $result[] = array('ERREUR', 'COHERENCE', 0, 'Fichier csv non valide');
	        }
    	} else {
        	$result[] = array('ERREUR', 'COHERENCE ', 0, 'Appel en POST uniquement');
    	}
    	return $this->renderSimpleCsv($result, "grc");
	}
	
	public function executeStreamGrc(sfWebRequest $request)
	{
		ini_set('memory_limit', '2048M');
	  	set_time_limit(0);
	    $interproId = $request->getParameter('interpro');
	    $this->securizeInterpro($interproId);
	    if (!preg_match('/^INTERPRO-/', $interproId)) {
			$interproId = 'INTERPRO-'.$interproId;
	    }
	    $interpro = InterproClient::getInstance()->find($interproId);
		$rows = EtablissementAllView::getInstance()->findAllByZone($interpro->zone);
		$eClient = EtablissementClient::getInstance();
		$zClient = ConfigurationZoneClient::getInstance();
		$result = '';
		foreach($rows as $row) {
			$etablissement = $eClient->find($row->id);
			$compte = $etablissement->getCompteObject();
			$result .= $etablissement->identifiant;
			$result .= ';';
			$result .= $etablissement->num_interne;
			$result .= ';';
			$result .= str_replace('CONTRAT-', '', $etablissement->contrat_mandat);
			$result .= ';';
			$result .= str_replace('INTERPRO-', '', $etablissement->interpro);
			$result .= ';';
			$result .= $etablissement->siret;
			$result .= ';';
			$result .= $etablissement->cni;
			$result .= ';';
			$result .= $etablissement->cvi;
			$result .= ';';
			$result .= $etablissement->no_accises;
			$result .= ';';
			$result .= $etablissement->no_tva_intracommunautaire;
			$result .= ';';
			$result .= $etablissement->email;
			$result .= ';';
			$result .= $etablissement->telephone;
			$result .= ';';
			$result .= $etablissement->fax;
			$result .= ';';
			$result .= $etablissement->raison_sociale;
			$result .= ';';
			$result .= $etablissement->nom;
			$result .= ';';
			$result .= $etablissement->siege->adresse;
			$result .= ';';
			$result .= $etablissement->siege->commune;
			$result .= ';';
			$result .= $etablissement->siege->code_postal;
			$result .= ';';
			$result .= $etablissement->siege->pays;
			$result .= ';';
			$result .= $etablissement->famille;
			$result .= ';';
			$result .= $etablissement->sous_famille;
			$result .= ';';
			$result .= $etablissement->comptabilite->adresse;
			$result .= ';';
			$result .= $etablissement->comptabilite->commune;
			$result .= ';';
			$result .= $etablissement->comptabilite->code_postal;
			$result .= ';';
			$result .= $etablissement->comptabilite->pays;
			$result .= ';';
			$result .= $etablissement->service_douane;
			$result .= ';';
			$result .= '';
			$result .= ';';
			$result .= $etablissement->statut;
			$result .= ';';
			$result .= ($compte)? $compte->nom : '';
			$result .= ';';
			$result .= ($compte)? $compte->prenom : '';
			$result .= ';';
			$result .= ($compte)? $compte->fonction : '';
			$result .= ';';
			$result .= ($compte)? $compte->email : '';
			$result .= ';';
			$result .= ($compte)? $compte->telephone : '';
			$result .= ';';
			$result .= ($compte)? $compte->fax : '';
			$result .= ';';
			$result .= $etablissement->no_carte_professionnelle;
			$result .= ';';
			$zones = array();
			$zonesLibelles = array();
			$correspondances = array();
			if ($etablissement->exist('zones')) {
				foreach ($etablissement->zones as $zoneId => $zone) {
					if (!$zone->transparente) {
						$zones[] = $zClient->getGrcCode($zoneId);
						$zonesLibelles[] = $zClient->getGrcLibelle($zoneId);
					}
				}
			}
			if ($etablissement->exist('correspondances')) {
				foreach ($etablissement->correspondances as $inter => $id) {
						$correspondances[] = str_replace('INTERPRO-', '', $inter).':'.$id;
				}
			}
			$result .= ($zones)? implode('|', $zones) : '';
			$result .= ';';
			$result .= ($zonesLibelles)? implode('|', $zonesLibelles) : '';
			$result .= ';';
			$result .= ($correspondances)? implode('|', $correspondances) : '';
			$result .= "\n";
		}
	    if (!$result) {
			$this->response->setStatusCode(204);
			return $this->renderText(null);
	    }
	    $date = date('r');
	    $now = date('c');
		$this->response->setContentType('text/csv');
	    $this->response->setHttpHeader('md5', md5($result));
	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$interproId."_GRC_".$now.".csv");
	    $this->response->setHttpHeader('LastDocDate', $date);
	    $this->response->setHttpHeader('Last-Modified', $date);
	    return $this->renderText($result);
	}
  
  
  protected function vracCallback($interpro, $items)
  {
  		$vracs = array();
  		$configurationVrac = ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro);
  		foreach ($items as $item) {
  			$item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE] = $configurationVrac->formatTypesTransactionLibelle(array($item->value[VracDateView::VALUE_TYPE_CONTRAT_LIBELLE]));
  			$item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE] = $configurationVrac->formatCasParticulierLibelle(array($item->value[VracDateView::VALUE_CAS_PARTICULIER_LIBELLE]));
  			$item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE] = $configurationVrac->formatConditionsPaiementLibelle(array($item->value[VracDateView::VALUE_CONDITIONS_PAIEMENT_LIBELLE]));
  			$vracs[] = $item;
  		}
  		return $vracs;
  }
  
  
  protected function drmCallback($items)
  {
  		$drms = array();
  		$squeeze = null;
  		foreach ($items as $item) {
  			if ($item->value[DRMDateView::VALUE_TYPE] == 'DETAIL' && (is_null($item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX]) || $item->value[DRMDateView::VALUE_DETAIL_CVO_TAUX] < 0)) {
  				$squeeze = $item->value[DRMDateView::VALUE_IDDRM];
  			}
  			if ($item->value[DRMDateView::VALUE_IDDRM] != $squeeze) {
  				$drms[] = $item;
  			}
  		}
  		return $drms;
  }

  protected function renderCsv($items, $dateSaisieIndice, $type, $date = null, $interpro, $correspondances = array()) 
  {
    $this->setLayout(false);
    $csv_file = '';
    $startDate = ($date)? $date."_" : '';
    $lastDate = $date;
    $beginDate = '9999-99-99';
    $rc1 = chr(10);
    $rc2 = chr(13);
    $tableCorrespondances = $this->getTableCorrespondance($interpro);
    foreach ($items as $item) {
    	if ($tableCorrespondances && $correspondances) {
    		foreach ($correspondances as $correspondance) {
    			if ($item->value[$correspondance] && in_array($item->value[$correspondance], array_keys($tableCorrespondances))) {
    				$item->value[$correspondance] = $tableCorrespondances[$item->value[$correspondance]];
    			}
    		}
    	}
    	if ($type == 'DRM') {
    		$item->value[DRMDateView::VALUE_DETAIL_CVO_MONTANT] = round($item->value[DRMDateView::VALUE_DETAIL_CVO_MONTANT], 2);
    	}
        $csv_file .= str_replace(array($rc1, $rc2), array(' ', ' '), implode(';', str_replace(';', '-', $item->value)));
      	$csv_file .= "\n";
      	if ($lastDate < $item->value[$dateSaisieIndice]) {
      		$lastDate = $item->value[$dateSaisieIndice];
      	}
      	if ($beginDate > $item->value[$dateSaisieIndice]) {
      		$beginDate = $item->value[$dateSaisieIndice];
      	}
    }
    if (!$startDate) {
    	$startDate = $beginDate."_";
    }
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$type."_".$startDate.$lastDate.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText($csv_file);
  }
  
  protected function getTableCorrespondance($interproId)
  {
  	$interpro = InterproClient::getInstance()->find($interproId);
  	return $interpro->correspondances->toArray();
  }
	
  protected function renderSimpleCsv($items, $type, $date = null) 
  {
    $this->setLayout(false);
    $csv_file = '';
  	foreach ($items as $item) {
      		$csv_file .= implode(';', $item)."\n";
    }
    $lastDate = ($date)? $date : date('Ymd');
    if (!$csv_file) {
		$this->response->setStatusCode(204);
		return $this->renderText(null);
    }
    $this->response->setContentType('text/csv');
    $this->response->setHttpHeader('md5', md5($csv_file));
    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$lastDate."_resultat_import_".$type.".csv");
    $this->response->setHttpHeader('LastDocDate', $lastDate);
    $this->response->setHttpHeader('Last-Modified', date('r', strtotime($lastDate)));
    return $this->renderText(utf8_decode($csv_file));
  }

  // A TESTER
  public function executeUploadEtablissements(sfWebRequest $request) 
  {  
  	$this->forward404Unless($this->interpro = InterproClient::getInstance()->getById($request->getParameter("id")));
    $this->securizeInterpro($this->interpro);
  	$this->formUploadCsv = new UploadCSVForm();
  	if ($request->isMethod(sfWebRequest::POST) && $request->getFiles('csv')) {
  		$this->formUploadCsv->bind($request->getParameter('csv'), $request->getFiles('csv'));
  		if ($this->formUploadCsv->isValid()) {
  			$file = $this->formUploadCsv->getValue('file');
  			$this->interpro->storeAttachment($file->getSavedName(), 'text/csv', 'etablissements.csv');
  			unlink($file->getSavedName());
  			$import = new ImportEtablissementsCsv($this->interpro);
  			$import->updateOrCreate();
  		}
  	}
  	$this->setLayout(false);
  	$this->response->setContentType('text/plain');
  }

}
