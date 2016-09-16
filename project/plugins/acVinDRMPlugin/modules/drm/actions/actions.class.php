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

        /*if ($drm->periode > DRMClient::getInstance()->getCurrentPeriode()) {
            $this->getUser()->setFlash('erreur_drm', 'Impossible de faire une DRM future');
            $this->redirect('drm_mon_espace', $etablissement);
        }*/

        /* if ($drm->isDebutCampagne() && !$drm->hasDaidsCampagnePrecedente()) {
          $this->getUser()->setFlash('erreur_drm', 'Impossible de faire la DRM '.$drm->periode.' sans la DAI/DS '.$drm->getCampagnePrecedente());
          $this->redirect('drm_mon_espace', $etablissement);
          } */
    		if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            	$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_PAPIER;
            } else {
            	$drm->mode_de_saisie = DRMClient::MODE_DE_SAISIE_DTI;
            }
        $drm->save();
        $this->redirect('drm_informations', $drm);
    }
    
    /**
     *
     * @param sfWebRequest $request 
     */
    public function executeImport(sfWebRequest $request) {
        $drm = $this->getRoute()->getDRM();
        if ($drm->isFictive()) {
        	$drm = $drm->getDRM();
        }
        $etablissement = $this->getRoute()->getEtablissement();
        

        $formUploadCsv = new UploadCSVForm();

        $result = array();
        if ($request->isMethod('post')) {
        	$formUploadCsv->bind($request->getParameter($formUploadCsv->getName()), $request->getFiles($formUploadCsv->getName()));
        	if ($formUploadCsv->isValid()) {
        		try {
        			$file = sfConfig::get('sf_data_dir') . '/upload/' . $formUploadCsv->getValue('file')->getMd5();
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
        				$drm->constructId();
        				$errors = 0;
        				if($drmCsvEdi->getCsvDoc()->getStatut() != "VALIDE") {
        					foreach($drmCsvEdi->getCsvDoc()->erreurs as $erreur) {
        						$result[] = array('ERREUR', 'CSV', $erreur->num_ligne, $erreur->diagnostic, $erreur->csv_erreur);
        						$errors++;
        					}
        				}
        				if ($drm->identifiant != $etablissement->getIdentifiant()) {
        					$result[] = array('ERREUR', 'ACCES', null, "Import restreint à l'établissement ".$etablissement->getIdentifiant());
        					$errors++;
        				}
        				if (!$etablissement->hasDroit(EtablissementDroit::DROIT_DRM_DTI)) {
        					$result[] = array('ERREUR', 'ACCES', null, "L'établissement ".$etablissement->getIdentifiant()." n'est pas autorisé à déclarer des DRMs");
        					$errors++;
        				}
        				if (!$errors) {
        					$drm->update();
        					$validation = new DRMValidation($drm);
        
        					if (!$validation->isValide()) {
        						foreach ($validation->getErrors() as $error) {
        							$result[] = array('ERREUR', 'CSV', null, str_replace('Erreur, ', '', $error));
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
        			$result[] = array('ERREUR', 'CSV', null, $e->getMessage());
        		}
        	} else {
        		$result[] = array('ERREUR', 'ACCES', null, 'Fichier csv non valide');
        	}
        		
        } else {
        	$result[] = array('ERREUR', 'ACCES ', null, 'Seules les requêtes de type POST sont acceptées');
        }
        
        $this->logs = $result;
        $this->etablissement = $etablissement;
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
            $drm->setCurrentEtapeRouting('recapitulatif');
            $this->redirect($drm->getCurrentEtapeRouting(), $drm);
        } elseif ($etape = $drm->etape) {
            $this->redirect($drm->getCurrentEtapeRouting(), $drm);
        } else {
            $drm->setCurrentEtapeRouting('ajouts_liquidations');
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
        if (!$drm->isNew() && !$drm->isValidee()) {
            /*if ($drm->hasVersion()) {
            	$drm->updateVracVersion();
                if ($previous = $drm->getMother()) {
                    $previous->updateVrac();
                }
            }*/
            $campagneDrm = $drm->campagne;
            $periodeDrm = $drm->periode;
            
            $hasVersion = $drm->hasVersion();
            $previous = ($drm->getPreviousVersion())? $drm->findDocumentByVersion($drm->getPreviousVersion()) : null;
            $mother = $drm->getMother();
            $bilan = BilanClient::getInstance()->findOrCreateByIdentifiant($etablissement->identifiant, 'DRM');
            $drm->delete();
            
            if ($hasVersion) {
            	if ($previous) {
            		$previous->updateBilan();
            	} elseif($mother) {
            		$mother->updateBilan();
            	}
            } else {            
            	$bilan->updateDRMManquantesAndNonSaisiesForCampagne($campagneDrm,$periodeDrm);
            	$bilan->save();
            }
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
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->updateLastDrmSession($this->etablissement);
        $this->historique = DRMClient::getInstance()->getDRMHistorique($this->etablissement->identifiant);
        $this->formCampagne = new DRMCampagneForm($this->etablissement->identifiant);
        $this->hasDrmEnCours = $this->historique->hasDRMInProcess();
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

    protected function hasNewDRM($historique, $identifiant) {
        if ($historique->getLastPeriode(false) >= $historique->getCurrentPeriode()) {
            return false;
        }
        if ($historique->hasDRMInProcess()) {
            return false;
        }
        return true;
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
        $this->form = new DRMDeclaratifForm($this->drm);
        $this->hasFrequencePaiement = ($this->drm->declaratif->paiement->douane->frequence) ? true : false;
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->drm = $this->form->save();
                $this->drm->setCurrentEtapeRouting('validation');
                $this->redirect('drm_validation', $this->drm);
            }
        }
    }

    public function executePaiementFrequenceFormAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $drm = $this->getRoute()->getDRM();
        return $this->renderText($this->getPartial('popupFrequence', array('drm' => $drm)));
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
    }

    /**
     * Executes mouvements generaux action
     *
     * @param sfRequest $request A request object
     */
    public function executeValidation(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->drm = $this->getRoute()->getDRM();
        $this->drm->storeDroits(array());
        $this->droits_circulation = new DRMDroitsCirculation($this->drm);
        $this->drmValidation = $this->drm->validation(array('stock' => 'warning', 'is_operateur' => $this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)));
        $this->engagements = $this->drmValidation->getEngagements();
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
            $this->engagements = array();
        }
        $this->form = new DRMValidationForm($this->drm, array('engagements' => $this->engagements));
        
        $this->drmCiel = $this->drm->getOrAdd('ciel');
        
        
        if (!$request->isMethod(sfWebRequest::POST)) {

            return sfView::SUCCESS;
        }

        $this->form->bind($request->getParameter($this->form->getName()));
        if (!$this->form->isValid() || !$this->drmValidation->isValide()) {

            return sfView::SUCCESS;
        }
		$values = $this->form->getValues();
		
        $this->drm = $this->form->save();
        
        if (isset($values['brouillon']) && $values['brouillon']) 
        {
        	$this->redirect('drm_validation', $this->drm);
        }
        
        $this->drm->validate();
        
        // CIEL ==============
	    $erreursCiel = false;
        if (!$this->drmCiel->isTransfere()) {
	        if ($this->getUser()->getCompte()->isTiers() && $this->getUser()->getCompte()->dematerialise_ciel) {
	        	$export = new DRMExportCsvEdi($this->drm);
	        	if ($xml = $export->exportEDI('xml')) {
	        		try {
	        			$service = new CielService();
	        			$this->drmCiel->xml = $service->transfer($xml);
	        		} catch (sfException $e) {
	        			$this->drmCiel->xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><reponse-ciel><erreur-interne><message-erreur>'.$e->getMessage().'</message-erreur></erreur-interne></reponse-ciel>';
	        		}
	        	} else {
	        		$this->drmCiel->transfere = 0;
	        		$this->drmCiel->xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><reponse-ciel><erreur-interne><message-erreur>Une erreur est survenue à la génération du XML.</message-erreur></erreur-interne></reponse-ciel>';
	        	}
	        }
	        $this->drmCiel->setInformationsFromXml();
	        if ($this->drmCiel->hasErreurs()) {
	        	$this->drm->devalide();
	        	$this->drm->etape = 'validation';
	        	$erreursCiel = true;
	        }
        }
        // CIEL ===============
        
        $this->drm->save();
        
        if ($erreursCiel) {
        	$this->redirect('drm_validation', $this->drm);
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

        return $this->redirect('drm_visualisation', array('sf_subject' => $this->drm, 'hide_rectificative' => 1));
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
        if ($this->drm->type == DRMFictive::TYPE) {
        	$this->drm->update();
        	$this->drm->setDroits();
        }
        $this->configurationProduits = null;
        $this->interpro = null;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$this->interpro = $this->getUser()->getCompte()->getGerantInterpro();
        	$this->configurationProduits = ConfigurationProduitClient::getInstance()->find($this->interpro->getOrAdd('configuration_produits'));
        }
        $this->droits_circulation = new DRMDroitsCirculation($this->drm);
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->hide_rectificative = $request->getParameter('hide_rectificative');
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
    }

    public function executeRectificative(sfWebRequest $request) {
        $this->etablissement = $this->getRoute()->getEtablissement();
        $drm = $this->getRoute()->getDRM();

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

    public function executeDownloadNotice() {
        return $this->renderPdf(sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . "docs/notice.pdf", "notice.pdf");
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
