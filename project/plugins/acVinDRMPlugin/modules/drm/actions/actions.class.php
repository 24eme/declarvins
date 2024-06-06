<?php

/**
 * drm actions.
 *
 * @package    declarvin
 * @subpackage drm
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class drmActions extends sfActions {

	public function preExecute()
  	{
  		try {
  			$etablissement = $this->getRoute()->getEtablissement();
        } catch (Error $e) {
            $etablissement = null;
  		} catch (Exeption $e) {
  			$etablissement = null;
  		}
  		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement) {
  			$configuration = ConfigurationClient::getCurrent();
  			$access = ($configuration->isApplicationOuverte($etablissement->interpro, 'drm', $etablissement))? true : false;
  			$this->forward404Unless($access);
  		}

  	}
    /**
     *
     * @param sfWebRequest $request
     */
    public function executeNouvelle(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();
        $etablissement = $this->getRoute()->getEtablissement();

        if ($drm->getHistorique()->hasDRMInProcess()) {
            $this->getUser()->setFlash('erreur_drm', 'Une DRM est déjà en cours de saisie.');
            $this->redirect('drm_mon_espace', $etablissement);
        }
    	if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
           	$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
        } else {
        	$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
        }
        $drm->save();
        if ($drm->isDebutCampagne())
        	$this->getUser()->setFlash('info_stocks', true);
        $this->redirect('drm_informations', $drm);
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeImport(sfWebRequest $request) {
  		set_time_limit(0);
        $drm = $this->getRoute()->getDRM();
        if ($drm->isFictive()) {
        	$drm = $drm->getDRM();
        }
        $etablissement = $this->getRoute()->getEtablissement();
        $historique = new DRMHistorique($etablissement->identifiant);

        $formUploadCsv = new UploadCSVForm();

        $send = true;

        $result = array();
        if ($request->isMethod('post')) {
        	if (!$historique->hasDRMInProcess()) {
        	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
        	if ($formUploadCsv->isValid()) {
        		try {
        			$file = sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5();
        			$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI_PLUS;
        			$drmCsvEdi = new DRMImportCsvEdi($file, $drm);
        			$drmCsvEdi->checkCSV();

        			if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
        				foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
        					if ($erreur->num_ligne > 0) {
        						$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
        					} else {
        						$result[] = array('ERREUR', 'CSV', null, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
        					}
        				}
        			} else {
        				$drmCsvEdi->importCsv();
        				$drm->constructId();
        				$errors = 0;
        				if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
        					foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
        						$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->id, $erreur->diagnostic, $erreur->csv_erreur);
        						$errors++;
        					}
        				}
        				if ($drm->identifiant != $etablissement->getIdentifiant()) {
        					$result[] = array('ERREUR', 'ACCES', null, 'error_access_nonpermis', "Import restreint à l'établissement ".$etablissement->getIdentifiant());
        					$errors++;
        				}
        				if (!$etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
        					$result[] = array('ERREUR', 'ACCES', null, 'error_access_droits', "L'établissement ".$etablissement->getIdentifiant()." n'est pas autorisé à déclarer des DRMs");
        					$errors++;
        				}
        				if (!$errors) {
        					$drm->update();
        					$validation = new DRMValidation($drm);

        					if (!$validation->isValide()) {
        						foreach ($validation->getErrors() as $error) {
        							$result[] = array('ERREUR', 'CSV', null, $error->getIdentifiant(), str_replace('Erreur, ', '', $error));
        						}
        					} else {
        						$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI_PLUS;
        						$drm->etape = 'validation';
        						$drm->save();
        						$this->redirect('drm_validation', $drm);
        					}
        				}
        			}

        		} catch(Exception $e) {
        			if (!$e->getMessage()) {
        				$send = false;
        			}
        			$result[] = array('ERREUR', 'CSV', null, 'error_500', $e->getMessage());
        		}
        	} else {
        		$result[] = array('ERREUR', 'ACCES', null, 'error_notvalid_inputcsv', 'Fichier csv non valide');
        	}
        } else {
        	$result[] = array('ERREUR', 'ACCES ', null, 'error_access_drmencours', 'Une DRM est en cours de saisie');
        }
        } else {
        	$result[] = array('ERREUR', 'ACCES ', null, 'error_access_rest', 'Seules les requêtes de type POST sont acceptées');
        }

        $this->logs = $result;
        $this->etablissement = $etablissement;

		if ($send && sfConfig::get('app_instance') != 'preprod') {
	        $interpro = $this->etablissement->getInterproObject();
	        $to = ($interpro)? array(sfConfig::get('app_email_to_notification'), $interpro->email_contrat_inscription): array(sfConfig::get('app_email_to_notification'));
	        if ($interpro && $interpro->identifiant == 'CIVP') {
	        	$to[] = $interpro->email_assistance_ciel;
	        }

	        $messageErreurs = "<ol>";
	        foreach ($this->logs as $log) {
	        	$messageErreurs .= "<li>".implode(';', $log)."</li>";
	        }
	        $messageErreurs .= "</ol>";
        	$message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), $to, "DeclarVins // Erreur import DTI+ pour ".$drm->identifiant, "Une transmission vient d'échouer pour ".$drm->identifiant."-".$drm->periode." :<br />".$messageErreurs)->setContentType('text/html');
        	$this->getMailer()->send($message);
        }

        $this->hasnewdrm = $this->hasNewDRM(DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant));
    }

    protected function hasNewDRM($historique, $identifiant = null) {
    	$last = $historique->getLastDRM();
    	$lastCiel = ($last)? $last->getOrAdd('ciel') : null;
    	if ($historique->hasDRMInProcess()) {
    		return false;
    	}
    	if ($lastCiel && $lastCiel->isTransfere() && !$lastCiel->isValide()) {
    		return false;
    	}
    	if (isset($this->campagne) && $this->campagne && DRMClient::getInstance()->buildCampagne($historique->getLastPeriode()) != $this->campagne) {
    		return false;
    	}
    	return true;
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeInit(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();
        $etablissement = $this->getRoute()->getEtablissement();
        $infos = array();
        $reinit_etape = $request->getParameter('reinit_etape', 0);
        if ($reinit_etape) {
            $drm->etape = 'recapitulatif';
            $drm->save();
            $this->redirect($drm->getCurrentEtapeRouting(), $drm);
        } elseif ($drm->etape) {
            $this->redirect($drm->getCurrentEtapeRouting(), $drm);
        } else {
            $drm->etape = 'ajouts_liquidations';
            $drm->save();
            $this->redirect('drm_informations', $drm);
        }
    }

    /**
     *
     * @param sfWebRequest $request
     */
    public function executeDelete(sfWebRequest $request) {
        throw new sfException('Obsolète');
    }

    public function executeDeleteOne(sfWebRequest $request) {
        $etablissement = $this->getRoute()->getEtablissement();
        $drm = $this->getRoute()->getDRM();

        $this->forward404If(($drm->isRectificative() && $drm->exist('ciel') && $drm->ciel->transfere && !$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)));

        if (!$drm->isNew() && !$drm->isValidee()) {
            if ($drm->hasVersion()) {
                if ($previous = $drm->getMother()) {
                    $previous->updateVrac();
                }
            }
            $campagneDrm = $drm->campagne;
            $periodeDrm = $drm->periode;

            $hasVersion = $drm->hasVersion();
            $previous = ($drm->getPreviousVersion())? $drm->findDocumentByVersion($drm->getPreviousVersion()) : null;
            $mother = $drm->getMother();
            $drm->delete();
            $this->redirect('drm_mon_espace', $etablissement);
        }
        throw new sfException('Vous ne pouvez pas supprimer cette DRM');
    }

    /**
     * Executes mon espace action
     *
     * @param sfRequest $request A request object
     */
    public function executeMonEspace(sfWebRequest $request) {
		ini_set('memory_limit', '256M');
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->updateLastDrmSession($this->etablissement);
        $this->historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);
        $this->formCampagne = new DRMCampagneForm($this->etablissement->identifiant);
        $this->hasDrmEnCours = $this->historique->hasDRMInProcess();
        $this->drmEnCours = null;
        if ($drmEnCours = $this->historique->getDRMInProcess()) {
        	$this->drmEnCours = DRMClient::getInstance()->find($drmEnCours->_id);
        }
        $drmSessionInfos = $this->getUser()->getAttribute('last_drm');
        if (!(isset($drmSessionInfos['periode']) && !empty($drmSessionInfos['periode'])) && $this->hasNewDRM($this->historique, $this->etablissement->identifiant)) {
            $periode = DRMClient::getInstance()->getLastPeriode($this->etablissement->identifiant);
            $infos = array();
            $infos['periode'] = $periode;
            $infos['valide'] = "NEW";
            $this->getUser()->setAttribute('last_drm', $infos);
        }
        if ($request->isMethod(sfWebRequest::POST)) {
            if ($this->hasDrmEnCours) {
                $this->getUser()->setFlash('erreur_drm', 'Une DRM est déjà en cours de saisie.');
                $this->redirect('drm_mon_espace', $this->etablissement);
            }
            $this->formCampagne->bind($request->getParameter($this->formCampagne->getName()));
            if ($this->formCampagne->isValid()) {
                $values = $this->formCampagne->getValues();
                $drm = DRMClient::getInstance()->createDoc($this->etablissement->identifiant, $values['campagne']);
                $drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
                $drm->save();

                $this->redirect('drm_informations', $drm);
            }
        }
    }

    /**
     * Executes historique action
     *
     * @param sfRequest $request A request object
     */
    public function executeHistorique(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->campagne = $request->getParameter('campagne');
    }

    /**
     * Executes informations action
     *
     * @param sfRequest $request A request object
     */
    public function executeInformations(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR);
        $this->form = new DRMInformationsForm(array(), array('is_admin' => $isAdmin));

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                if ($values['confirmation'] == "modification") {
                    $this->redirect('drm_modif_infos', $this->drm);
                }
                $this->drm->setEtablissementInformations($this->etablissement);
                $this->drm->setCurrentEtapeRouting('ajouts_liquidations');
                $this->redirect('drm_mouvements_generaux', $this->drm);
            }
        }
    }

    public function executeModificationInfos(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
    }

    public function executeDeclaratif(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->form = new DRMDeclaratifForm($this->drm);
        $this->hasFrequencePaiement = ($this->drm->declaratif->paiement->douane->frequence) ? true : false;
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->drm = $this->form->save();
                if ($request->getParameter('prev')) {
                    $this->drm->save();
                    if (($this->getUser()->getCompte()->isTiers() && $this->etablissement->isTransmissionCiel()) || $this->drm->isNegoce()) {
                        $this->redirect('drm_crd', $this->drm);
                    } else {
                        $this->redirect('drm_vrac', ['sf_subject' => $this->drm, 'precedent' => '1']);
                    }
                }
                $this->drm->etape = 'validation';
                $this->drm->save();
                $this->redirect('drm_validation', $this->drm);
            }
        }
    }

    public function executePaiementFrequenceFormAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $drm = $this->getRoute()->getDRM();
        return $this->renderText($this->getPartial('popupFrequence', array('drm' => $drm)));
    }

    public function executeGetXml(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDRM();
    	$xml = $drm->ciel->diff;
    	$this->forward404Unless($xml);
    	$dom = new DOMDocument();
    	$dom->loadXML($xml);
    	$dom->formatOutput = true;
    	$this->getResponse()->setHttpHeader('md5', md5($xml));
    	$this->getResponse()->setHttpHeader('LastDocDate', date('r'));
    	$this->getResponse()->setHttpHeader('Last-Modified', date('r'));
    	$this->getResponse()->setContentType('text/xml');
    	$this->getResponse()->setHttpHeader('Content-Disposition', "attachment; filename=".$drm->_id.".xml");
    	return $this->renderText($dom->saveXML());
    }

    public function executeGetDtiPlusFile(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDRM();
        $csv = $drm->getDtiPlusCSV();
    	$this->forward404Unless($csv);
        $content = $csv->getFileContent();
        $this->forward404Unless($content);
    	$this->getResponse()->setContentType('text/csv');
        $this->response->setHttpHeader('md5', md5($content));
	    $this->response->setHttpHeader('Content-Disposition', "attachment; filename=".$csv->getFileName());
	    return $this->renderText($content);
    }

    public function executePayerReport(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDRM();
    	$drm->payerReport();
    	$drm->save();
    	return $this->redirect('drm_validation', array('sf_subject' => $drm));
    }

    public function executeTransferCiel(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->postVars = $request->getPostParameters();
        if (isset($this->postVars['drm_validation']['brouillon']) && $this->postVars['drm_validation']['brouillon']) {
        	foreach ($this->postVars as $id => $vars) {
        		foreach ($vars as $name => $value) {
        			$this->getRequest()->setParameter($id.'['.$name.']', $value);
        		}
        	}
        	$this->forward('drm','validation');
        }
    }

    public function executeRetransferCiel(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
        if ($this->drm->isFictive()) {
        	$this->drm = $this->drm->getDRM();
        }
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->drmCiel = $this->drm->getOrAdd('ciel');
        $this->postVars = array('drm_validation' => array('retransmission' => 1));
    	$this->url = $this->generateUrl('drm_retransfer_ciel', array('sf_subject' => $this->drm));

        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->etablissement->isTransmissionCiel()) {
        	if ($request->isMethod(sfWebRequest::POST)) {
	        	$export = new DRMExportCsvEdi($this->drm);
		        if ($xml = $export->exportEDI('xml')) {
		        	try {
		        		$service = new CielService($this->etablissement->interpro);
		        		$this->drmCiel->xml = $service->transfer($xml);
		        	} catch (sfException $e) {
		        		$this->getUser()->setFlash('error', "Une erreur est survenue lors du dialogue avec CIEL");
		        		return $this->redirect('drm_visualisation', array('sf_subject' => $this->drm));
		        	}
		        }
		        $this->drmCiel->setInformationsFromXml();
		        if ($this->drmCiel->hasErreurs()) {
							if ($this->drm->hasVersion()) {
								$this->drm->updateVracVersion();
							}
		        	$this->drm->devalide();
		        }
		        $this->drm->save();
		        if ($this->drm->isValidee()) {
		        	$this->getUser()->setFlash('notice', "DRM transmise avec succès à CIEL");
		        	$this->redirect('drm_visualisation', array('sf_subject' => $this->drm));
		        } else {
		        	$this->redirect('drm_validation', array('sf_subject' => $this->drm));
		        }
        	}
    	} else {
    		return $this->redirect404();
    	}
    	$this->setTemplate('transferCiel');
    }

    public function executeReouvrir(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();
		$interpro = ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR))? $this->getUser()->getCompte()->getGerantInterpro()->_id : null;
		$this->forward404Unless($this->drm->isNonFactures($interpro));
        if ($this->drm->isFictive()) {
        	$this->drm = $this->drm->getDRM();
        }
        $this->etablissement = $this->getRoute()->getEtablissement();

        $this->drm->cleanCiel();
        $this->drm->devalide();
        $this->drm->save();

        $this->redirect('drm_init', array('sf_subject' => $this->drm));
    }

    /**
     * Executes mouvements generaux action
     *
     * @param sfRequest $request A request object
     */
    public function executeValidation(sfWebRequest $request) {
  		set_time_limit(90);
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->drm = $this->getRoute()->getDRM();
        $isAdmin = $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR);
        if ($isAdmin) {
        	$this->drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
        }
        $this->drm->storeDroits(array());
        $this->droits_circulation = new DRMDroitsCirculation($this->drm);
        $this->drmValidation = $this->drm->validation(array('stock' => 'warning', 'is_operateur' => $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)));

        $this->engagements = $this->drmValidation->getEngagements();
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->engagements = array();
        }

        $this->form = new DRMValidationForm($this->drm, array('is_admin' => $isAdmin, 'engagements' => $this->engagements));

        $this->drmCiel = $this->drm->getOrAdd('ciel');


        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));

		$values = $this->form->getValues();

        if (isset($values['brouillon']) && $values['brouillon'] && $this->form->isValid())
        {
        	$values = $this->form->getValues();
        	$this->drm = $this->form->save();
        	return $this->redirect('drm_validation', $this->drm);
        }

        if (!$this->form->isValid() || !$this->drmValidation->isValide()) {

            return sfView::SUCCESS;
        }

        $this->drm = $this->form->save();

        if ($values['manquants']['contrats'])
        {
        	$details = $this->drm->getDetailsVracSansContrat();
        	if (count($details) > 0) {
        		$compte = $this->etablissement->getCompteObject();
        		if ($compte->email) {
        			Email::getInstance()->vracRelanceFromDRM($this->drm, $details, $compte->email);
        		}
        	}
        }
        $this->drm->validate();
        // CIEL ==============
	    $erreursCiel = false;
        if (!$isAdmin) {
        if (!$this->drmCiel->isTransfere() && !$this->drm->hasVersion() && $request->getParameter('transfer_ciel')) {
	        if ($this->etablissement->isTransmissionCiel()) {
	        	$export = new DRMExportCsvEdi($this->drm);
	        	if ($xml = $export->exportEDI('xml')) {
	        		try {
	        			$service = new CielService($this->etablissement->interpro);
	        			$this->drmCiel->xml = $service->transfer($xml);
	        		} catch (sfException $e) {
	        			$this->drmCiel->xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><reponse-ciel><erreur-interne><message-erreur>'.$e->getMessage().'</message-erreur></erreur-interne></reponse-ciel>';
	        		}
	        	} else {
	        		$this->drmCiel->transfere = 0;
	        		$this->drmCiel->valide = 0;
	        		$this->drmCiel->xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><reponse-ciel><erreur-interne><message-erreur>Une erreur est survenue à la génération du XML.</message-erreur></erreur-interne></reponse-ciel>';
	        	}
	        $this->drmCiel->setInformationsFromXml();
	        if ($this->drmCiel->hasErreurs()) {
	        	$interpro = $this->etablissement->getInterproObject();
	        	$to = ($interpro)? array(sfConfig::get('app_email_to_notification'), $interpro->email_contrat_inscription): array(sfConfig::get('app_email_to_notification'));
	        	if ($interpro && $interpro->identifiant == 'CIVP') {
	        		$to[] = $interpro->email_assistance_ciel;
	        	}
	        	$this->drm->devalide();
	        	$this->drm->etape = 'validation';
	        	$erreursCiel = true;
	        	$messageErreurs = "<ol>";
	        	foreach ($this->drmCiel->getErreurs() as $erreur) {
	        		$messageErreurs .= "<li>$erreur</li>";
	        	}
	        	$messageErreurs .= "</ol>";
	        	$message = $this->getMailer()->compose(sfConfig::get('app_email_from_notification'), $to, "DeclarVins // Erreur transmision XML pour ".$this->drm->_id, "Une transmission vient d'échouer pour ".$this->drm->_id." (".$this->drm->declarant->no_accises.") :<br />".$messageErreurs)->setContentType('text/html');
	        	$this->getMailer()->send($message);
	        }
	        }
        }
    	}
        if ($this->drm->hasVersion() && $this->drmCiel->isTransfere()) {
        	$this->drm->ciel->valide = 1;
        }
        // CIEL ===============

        $this->drm->save();

        if ($this->drmCiel->isTransfere()) {
        	Email::getInstance()->cielSended($this->drm);
        }

        if ($erreursCiel) {
        	return $this->redirect('drm_validation', $this->drm);
        }

    	if ($this->drm->needNextVersion()) {
	      $generate = true;
	      $nb_generate = 0;
	      $drm_version_suivante = $this->drm->generateNextVersion();
	      if ($drm_version_suivante) {
	      if ($drm_version_suivante->isRectificative()) {
	      	$drm_version_suivante->save();
	      	$this->getUser()->setFlash('drm_next_version', $drm_version_suivante->_id);
	      } else {
	      	while($generate) {
		      	$validation_drm_version_suivante = $drm_version_suivante->validation(array('stock' => 'warning'));
		      	if ($validation_drm_version_suivante->isValide()) {
		      		$drm_version_suivante->validate();
		      		$drm_version_suivante->save();
		      		$nb_generate++;
		      	} else {
		      		$drm_version_suivante->save();
		      		$this->getUser()->setFlash('drm_next_version', $drm_version_suivante->_id);
		      		$generate = false;
		      	}
		      	if ($drm_version_suivante->needNextVersion()) {
		      		$drm_version_suivante = $drm_version_suivante->generateNextVersion();
		      		if (!$drm_version_suivante) {
		      			$generate = false;
		      		}
		      	} else {
		      		$generate = false;
		      	}
		      }
		       $this->getUser()->setFlash('drm_generate_version', $nb_generate);
	      }
    	  }
	    }

        $this->notifieVolumesSurveilles($this->drm);

        return $this->redirect('drm_visualisation', array('sf_subject' => $this->drm, 'hide_rectificative' => 1));
    }

    private function notifieVolumesSurveilles($drm) {
        foreach (InterproClient::$_drm_interpros as $interpro) {
            $volumes = $drm->getVolumesSurveilles($interpro);
            if ($volumes) {
                Email::getInstance()->volumesSurveilles($drm, $volumes, InterproClient::getInstance()->find($interpro));
            }
        }
        if ($drm->hasUtilisationReserveInterpro()) {
            Email::getInstance()->volumesReserveInterpro($drm);
        }
    }

    private function updateLastDrmSession($etablissement) {
        if ($etablissement) {
            $historique = new DRMHistorique($etablissement->identifiant);
            $infos = array();
            if ($drm = $historique->getLastDRM()) {
                $infos['periode'] = $drm->periode;
                $infos['valide'] = (int) $drm->isValidee();
            }
            $this->getUser()->setAttribute('last_drm', $infos);
        }
    }

    public function executeForceValidationCiel(sfWebRequest $request) {
		set_time_limit(90);
    	$this->etablissement = $this->getRoute()->getEtablissement();
    	$this->drm = $this->getRoute()->getDRM();
    	if ($this->drm->isFictive()) {
    		$this->drm = $this->drm->getDRM();
    	}
    	$condition = $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $this->drm->hasVersion();
    	$this->forward404Unless($condition);
    	$isDelete = false;
    	if (!$this->drm->hasRectifications()) {
	    	if ($mother = $this->drm->getMother()) {
	    		$this->drm->delete();
	    		$this->drm = $mother;
    			$isDelete = true;
	    	}
    	}
    	if (!$isDelete) {
    		$this->drm->validate();
    	}
    	$this->drm->ciel->valide = 1;
    	$this->drm->save();
    	return $this->redirect('drm_visualisation', array('sf_subject' => $this->drm));
    }

    public function executeShowError(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();
        $drmValidation = new DRMValidation($drm, array('is_operateur' => $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)));
        $controle = $drmValidation->find($request->getParameter('type'), $request->getParameter('identifiant_controle'));
        $this->forward404Unless($controle);
        $this->getUser()->setFlash('control_message', $controle->getMessage());
        $this->getUser()->setFlash('control_css', "flash_" . $controle->getType());
        $this->redirect($controle->getLien());
    }

    /**
     * Executes mouvements generaux action
     *
     * @param sfRequest $request A request object
     */
    public function executeVisualisation(sfWebRequest $request) {
        $this->drm = $this->getRoute()->getDRM();

        $this->historique = new DRMHistorique($this->drm->identifiant);
        if ($this->drm->type == DRMFictive::TYPE) {
        	$this->drm->update();
        	$this->drm->setDroits();
        }
        $this->configurationProduits = null;
        $this->interpro = null;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
        	$this->configurationProduits = ConfigurationProduitClient::getInstance()->getByInterpro($this->interpro->identifiant, $this->drm->getDateDebutPeriode());
        }
        $this->droits_circulation = new DRMDroitsCirculation($this->drm);
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->hide_rectificative = 0; //$request->getParameter('hide_rectificative');
        $this->drm_next_version = $this->getUser()->getFlash('drm_next_version');
        if ($this->drm_next_version) {
        	$this->drm_next_version = DRMClient::getInstance()->find($this->drm_next_version);
        }
        $this->drm_generate_version = $this->getUser()->getFlash('drm_generate_version');
        //$this->drm_suivante = $this->drm->getSuivante();
        $this->drm_precedente_version_id = DRMClient::getInstance()->buildId($this->drm->identifiant,$this->drm->periode,$this->drm->getPreviousVersion());
        $this->drm_precedente_version = DRMClient::getInstance()->find($this->drm_precedente_version_id);
        $this->masterVersion = $this->drm->getMaster();
        $this->mouvements = DRMMouvementsConsultationView::getInstance()->getMouvementsByEtablissementAndPeriode($this->drm->identifiant, $this->drm->periode);

        if ($this->drm->hasIncitationDS() && !$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->getUser()->setFlash('incitation_stock_rose', 1);
        }
    }

    public function executeRectificative(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $drm = $this->getRoute()->getDRM();

        $this->forward404Unless($drm->isRectifiable());

        if ($drm->getHistorique()->hasDRMInProcess()) {
            $this->getUser()->setFlash('erreur_drm', 'Une DRM est déjà en cours de saisie.');
            $this->redirect('drm_visualisation', array('sf_subject' => $drm));
        }

        $drm_rectificative = $drm->generateRectificative();
    	if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            	$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
            } else {
            	$drm_rectificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
            }
        $drm_rectificative->save();

        return $this->redirect('drm_init', $drm_rectificative);
    }

    public function executeModificative(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $drm = $this->getRoute()->getDRM();

        $this->forward404Unless($drm->isModifiable());

        if ($drm->getHistorique()->hasDRMInProcess()) {
            $this->getUser()->setFlash('erreur_drm', 'Une DRM est déjà en cours de saisie.');
            $this->redirect('drm_visualisation', array('sf_subject' => $drm));
        }

        $drm_modificative = $drm->generateModificative();
    	if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            	$drm_modificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
            } else {
            	$drm_modificative->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
            }
        $drm_modificative->save();

        return $this->redirect('drm_init', $drm_modificative);
    }

    public function executeRetourRefresh(sfWebRequest $request) {
        $etablissement = $this->getRoute()->getEtablissement();
        $drm = $this->getRoute()->getDRM();
        $this->setLayout(false);
        $pathScript = realpath('../bin/updateRetourCielDrm.sh');
        if(!$pathScript){
            throw new sfException("Le script de mis à jour n'existe pas");
        }
        $periode = $drm->periode;
        $numeroAccise = $etablissement->no_accises;
        $interpro = str_replace('INTERPRO-', '', $etablissement->interpro);
        $id = $drm->_id;
        $cmd = "bash $pathScript \"$interpro\" \"$numeroAccise\" \"$periode\" \"$id\"";
        $retour = shell_exec($cmd);
        if($rectif = DRMClient::getInstance()->find($drm->_id."-R01")){
            return $this->redirect('drm_validation', array('sf_subject' => $rectif));
        }
        return $this->redirect('drm_visualisation', array('sf_subject' => $drm));
    }

    /**
     * Executes mouvements generaux action
     *
     * @param sfRequest $request A request object
     */
    public function executePdf(sfWebRequest $request) {

        ini_set('memory_limit', '512M');
        $this->drm = $this->getRoute()->getDRM();
        if ($this->drm->type == DRMFictive::TYPE) {
        	$this->drm->update();
        }
        $this->drm->setDroits();
        $pdf = new ExportDRMPdf($this->drm);

        return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
    }

    public function executeValidee() {
        $this->etablissement = $this->getRoute()->getEtablissement();
    }

    public function executeNonValidee() {
        $this->etablissement = $this->getRoute()->getEtablissement();
    }

    protected function renderPdf($path, $filename) {
        $this->getResponse()->setHttpHeader('Content-Type', 'application/pdf');
        $this->getResponse()->setHttpHeader('Content-disposition', 'attachment; filename="' . $filename . '"');
        $this->getResponse()->setHttpHeader('Content-Transfer-Encoding', 'binary');
        $this->getResponse()->setHttpHeader('Content-Length', filesize($path));
        $this->getResponse()->setHttpHeader('Pragma', '');
        $this->getResponse()->setHttpHeader('Cache-Control', 'public');
        $this->getResponse()->setHttpHeader('Expires', '0');
        return $this->renderText(file_get_contents($path));
    }

}
