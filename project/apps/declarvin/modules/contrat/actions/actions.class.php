<?php

/**
 * contrat actions.
 *
 * @package    declarvin
 * @subpackage contrat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class contratActions extends sfActions
{

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {

    }
    
    public function executeValide(sfWebRequest $request) {
    	$nocontrat = $request->getParameter('nocontrat');
    	$this->contrat = ContratClient::getInstance()->retrieveById($nocontrat);
    }

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeNouveau(sfWebRequest $request) {
    	$new = true;
    	if ($object = ContratClient::getInstance()->retrieveById($request->getParameter('nocontrat'))) {
    		if ($object->valide) {
    			return $this->redirect("contrat_valide");
    		}
    		$new = false;
        	$this->nbEtablissement = $request->getParameter('nb_etablissement', count($object->etablissements));
    	} else {
    		$object = new Contrat();
    		$object->valide = 0;
        	$this->nbEtablissement = $request->getParameter('nb_etablissement', 1);
    	}
        $this->form = new ContratForm($object, array('nbEtablissement' => $this->nbEtablissement));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $contrat = $this->form->save();
                if ($new) {
	                $compte = new CompteTiers();
                } else {
                	$compte = _CompteClient::getInstance()->find($contrat->compte);
                }
	            $compte->generateByContrat($contrat);
           		$compte->statut = _Compte::STATUT_FICTIF;
           		$compte->valide = 0;
        		$compte->contrat_valide = 0;
	            $compte->save();
                $contrat->setCompte($compte->get('_id'));
                $contrat->save();
                $this->getUser()->setAttribute('contrat_id', $contrat->get('_id'));
                $this->redirect('contrat_etablissement_modification', array('indice' => 0));
            }
        }
    }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeModificationEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$this->forward404Unless($request->hasParameter('indice'));
  	$this->recapitulatif = $request->getParameter('recapitulatif');
    if ($this->contrat->valide) {
    	return $this->redirect("contrat_valide");
    }
  	$this->indice = $indice = $request->getParameter('indice');
  	$nextIndice = $indice + 1;
    $this->form = new ContratEtablissementModificationForm($this->contrat->etablissements->get($indice));
    if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
            $this->form->save();
            if ($this->contrat->etablissements->exist($nextIndice)) {
            	if ($this->recapitulatif)
            		$this->redirect('@contrat_etablissement_recapitulatif');
            	else {
            		$this->getUser()->setFlash('success', 'Modification effectuées');
            		$this->redirect('contrat_etablissement_modification', array('indice' => $nextIndice));
            	}
            } else {
                $this->redirect('@contrat_etablissement_recapitulatif');
            }
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeNouveauEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
    if ($this->contrat->valide) {
    	return $this->redirect("contrat_valide");
    }
    $this->form = new ContratEtablissementModificationForm($this->contrat->etablissements->add());
    if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
           $this->form->save();
           $this->redirect('@contrat_etablissement_recapitulatif');
        }
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeSuppressionEtablissement(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$this->forward404Unless($request->hasParameter('indice'));
    if ($this->contrat->valide) {
    	return $this->redirect("contrat_valide");
    }
  	$indice = $request->getParameter('indice');
  	if ($indice < 1) {
  		$this->redirect('contrat_etablissement_modification', array('indice' => $indice));
  	}
  	$this->recapitulatif = $request->getParameter('recapitulatif');
  	$nextIndice = $indice + 1;
  	$this->contrat->etablissements->remove($indice);
  	$this->contrat->save();
  	if ($this->contrat->etablissements->exist($nextIndice)) {
  		if ($this->recapitulatif)
  			$this->redirect('@contrat_etablissement_recapitulatif');
  		else
  			$this->redirect('contrat_etablissement_modification', array('indice' => $indice));
  	}  else {
  		 
  		if ($this->recapitulatif)
  			$this->redirect('@contrat_etablissement_recapitulatif');
  		else
  			$this->redirect('contrat_nouveau', array('nocontrat' => $this->contrat->no_contrat));
  	}
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeRecapitulatif(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
    if ($this->contrat->valide) {
    	return $this->redirect("contrat_valide");
    }
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeConfirmation(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	if (!$request->getParameter('send', null)) {
  		$this->contrat->valide = 1;
  		$this->contrat->save();
        $compte = $this->contrat->getCompteObject();
        $compte->contrat_valide = 1;
        $compte->save();
  		$this->sendContratMandat($this->contrat);
  		$this->redirect('contrat_etablissement_confirmation', array('send' => 'sended'));
  	}
  	$this->form = new CompteModificationEmailForm($this->contrat);
  	$this->showForm = false;
    if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
           $contrat = $this->form->save();
           $compte = $contrat->getCompteObject();
           $compte->email = $contrat->email;
           $compte->save();
           $this->getUser()->setFlash('success', 'Modification prise en compte, vous devriez recevoir un nouvel email');
           $this->redirect('@contrat_etablissement_confirmation');
        }
        else {
        	$this->showForm = true;
        }
    }
  }
  
  protected function sendContratMandat($contrat) {
  	$pdf = new ExportContratPdf($contrat);
  	$pdf->generate();
  	
  	$interpros = $this->getInterprosByZonesForInscription($contrat->getConfigurationZonesForSend());
    Email::getInstance()->sendContratMandat($contrat, $contrat->email, $interpros);
  	foreach ($interpros as $interpro) {
  		if ($interpro->email_contrat_inscription) {
  			Email::getInstance()->sendContratMandat($contrat, $interpro->email_contrat_inscription, array($interpro));
  		}	
  	}
  }
  
  protected function getInterprosByZonesForInscription($zones)
  {
  	$result = array();
  	foreach ($zones as $zone) {
  		if (!$zone->transparente) {
  			$result = array_merge($result, $zone->getInterprosForInscriptions());
  		}
  	}
  	return $result;
  }
  
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executePdf(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$pdf = new ExportContratPdf($this->contrat);
	return $this->renderText($pdf->render($this->getResponse()));
  }
}
