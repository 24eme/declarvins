<?php

class drm_recapActions extends sfActions
{
	public function preExecute()
  	{
  		try {
  			$etablissement = $this->getRoute()->getEtablissement();
  		} catch (Exeption $e) {
  			$etablissement = null;
  		}
  		if (!$this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR) && $etablissement) {
  			$configuration = ConfigurationClient::getCurrent();
  			$etabstest = array('C0356','C0662','C2262','C7598','C0469','C1046','C8664','C0662','C7002','T0001');
  			$access = ($configuration->isApplicationOuverte($etablissement->interpro, 'drm') || in_array($etablissement->identifiant, $etabstest))? true : false;
  			$this->forward404Unless($access);
  		}
  		
  	}
    public function executeIndex(sfWebRequest $request) {
        $this->init();
		
        $this->setTemplate('index');
    }

    public function executeRedirectIndex(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDRM();
    	$first_certification = null;
        if(count($drm->declaration->certifications) > 0) {
        	foreach ($drm->declaration->certifications as $key => $certification) {
        		if (count($drm->declaration->certifications->get($key)->genres) > 0) {
        			$first_certification = $drm->declaration->certifications->get($key);
        			break;
        		}
        	}
        }
        $this->redirect('drm_recap', $first_certification);
    }

    public function executeRedirectLast(sfWebRequest $request) {
    	$drm = $this->getRoute()->getDRM();
    	$last_certification = null;
        if(count($drm->declaration->certifications) > 0) {        
        	foreach ($drm->declaration->certifications as $key => $certification) {
        		if (count($drm->declaration->certifications->get($key)->genres) > 0) {
        			$last_certification = $drm->declaration->certifications->get($key);
        		}
        	}
        }
        $this->redirect('drm_recap', $last_certification);
    }
    
    public function executeLieuAjoutAjax(sfWebRequest $request) {
        $this->forward404Unless($request->isXmlHttpRequest());
        $this->getResponse()->setContentType('text/json');
        $this->certification = $this->getRoute()->getObject();
        $this->drm = $this->getRoute()->getDRM();
        $config = ConfigurationClient::getCurrent();
        $certification = $this->getRoute()->getCertification();
    	$configurationProduits = null;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$interpro = $this->getUser()->getCompte()->getGerantInterpro();
        	$configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'));
        }

        $this->form = new DRMLieuAjoutForm($this->drm, $config, $certification, $configurationProduits);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $lieu = $this->form->addLieu();
                return $this->renderText(json_encode(array("success" => true,
                                                           "ajax" => true,
                                                           "url" => $this->generateUrl('drm_recap_ajout_ajax', $lieu))));
            }

            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/formLieuAjout', array('certification' => $this->certification, 'form' => $this->form)))));
        }
		
        return $this->renderText($this->getPartial('drm_recap/popupLieuAjout', array('certification' => $this->certification, 'form' => $this->form)));
    }
    
    public function executeLieu(sfWebRequest $request) {
        $this->init();
        $this->setTemplate('index');
    }

    public function executeAjoutAjax(sfWebRequest $request) 
    {
        $this->drm = $this->getRoute()->getDRM();
        $this->drm_lieu = $this->getRoute()->getDRMLieu();
        $config = ConfigurationClient::getCurrent();
        $certification = $this->getRoute()->getCertification();
        
        $this->forward404Unless($request->isXmlHttpRequest());
    	$configurationProduits = null;
        if ($this->getUser()->hasCredential(myUser::CREDENTIAL_OPERATEUR)) {
        	$interpro = $this->getUser()->getCompte()->getGerantInterpro();
        	$configurationProduits = ConfigurationProduitClient::getInstance()->find($interpro->getOrAdd('configuration_produits'));
        }

        $form = new DRMProduitAjoutForm($this->drm, $config, $certification, $this->drm_lieu->getHash(), $configurationProduits);

        if ($request->isMethod(sfWebRequest::POST)) {
            $this->getResponse()->setContentType('text/json');
            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                $form->addProduit();
                $this->drm->save();
                $this->getUser()->setFlash("notice", 'Le produit a été ajouté avec succès.');
                return $this->renderText(json_encode(array("success" => true, 
                                                           "url" => $this->generateUrl('drm_recap_lieu', $this->drm_lieu))));
            }
            return $this->renderText(json_encode(array("success" => false, 
                                                       "content" => $this->getPartial('formAjout', array('form' => $form, 'drm_lieu' => $this->drm_lieu)))));
        }
        return $this->renderPartial('popupAjout', array('form' => $form, 'drm_lieu' => $this->drm_lieu));
    }
    
    public function executeDetail(sfWebRequest $request) {
        $this->init();
        $this->detail = $this->getRoute()->getDRMDetail();
        $this->setTemplate('index');
    }
    
    public function executeUpdate(sfWebRequest $request) {
        $this->init();
  
        $this->form = new DRMDetailForm($this->getRoute()->getDRMDetail());
        $this->form->bind($request->getParameter($this->form->getName()));
        
        if($this->form->isValid()) {
        	$this->form->save();
            if ($request->isXmlHttpRequest()) {
				         		
                return $this->renderText(json_encode(array("success" => true,
                										   "content" => "",
                										   "document" => array("id" => $this->drm->get('_id'),
                										   					   "revision" => $this->drm->get('_rev'))
                										   )));
            } else {
                $this->redirect('drm_recap_lieu', $this->config_lieu);
            }
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->renderText(json_encode(array("success" => false, "content" => $this->getPartial('drm_recap/itemFormErrors', array('form' => $this->form)))));
        } else {
            $this->setTemplate('index');
        }
    }
    
    protected function init() {
        $this->form = null;
        $this->detail = null;
        $this->drm = $this->getRoute()->getDRM();
        $this->config_lieu = $this->getRoute()->getConfigLieu();
        $this->drm_lieu = $this->getRoute()->getDRMLieu();
        $this->produits = $this->drm_lieu->getProduits();
        $this->previous = $this->drm_lieu->getPreviousSisterWithMouvementCheck();
        $this->next = $this->drm_lieu->getNextSisterWithMouvementCheck();
    	$this->previous_certif = $this->drm_lieu->getCertification()->getPreviousSisterWithMouvementCheck();
    	$this->next_certif = $this->drm_lieu->getCertification()->getNextSisterWithMouvementCheck();
		$this->percent = 40;
		if (count($this->drm->getDetailsAvecVrac()) > 0) {
			$this->percent -= 10;
		}
		$config_certifications = ConfigurationClient::getCurrent()->getCertifications();
        $certifications = array();
        $current = 1;
        $find = false;
        foreach ($config_certifications as $certification_config) {
            if ($this->drm->declaration->certifications->exist($certification_config)) {
            	$certif = $this->drm->declaration->certifications->get($certification_config);
            	if ($certif->hasMouvementCheck() && count($certif->genres) > 0) {
	                $certifications[] = $certif;
	                if ($this->drm_lieu->getCertification()->getKey() == $certification_config) {
	                	$find = true;
	                }
	                if ($this->drm_lieu->getCertification()->getKey() != $certification_config && !$find) {
	                	$current++;
	                }
            	}
            }
        }
        $nbCertifs = count($certifications);
        $this->percent += round(((20 / $nbCertifs) * $current), 0, PHP_ROUND_HALF_UP);
    	$this->redirectIfNoMouvementCheck();
    }

    protected function redirectIfNoMouvementCheck() {    	
    	if (!$this->drm_lieu->hasMouvementCheck()) {
	    	if ($this->next) {
	        	$this->redirect('drm_recap_lieu', $this->next);
	        } elseif (!$this->next && $this->next_certif) {
	        	$this->redirect('drm_recap', $this->next_certif);
	        } else  {
	        	$this->redirect('drm_vrac', $this->drm);
	        }
    	}
    }
}
