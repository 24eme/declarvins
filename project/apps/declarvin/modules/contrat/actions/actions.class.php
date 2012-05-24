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

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeNouveau(sfWebRequest $request) {
    	$new = true;
    	if ($contrat = $request->getParameter('nocontrat')) {
    		$new = false;
    		$object = ContratClient::getInstance()->find('CONTRAT-'.$contrat);
    	} else {
    		$object = new Contrat();
    	}
        $this->nbEtablissement = $request->getParameter('nb_etablissement', 1);
        $this->form = new ContratForm($object, array('nbEtablissement' => $this->nbEtablissement));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $contrat = $this->form->save();
                if ($new) {
	                $compte = new CompteTiers();
	                $compte->generateByContrat($contrat);
	                $compte->save();
                	$contrat->setCompte($compte->get('_id'));
                }
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
  	$indice = $request->getParameter('indice');
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
  	$this->recapitulatif = $request->getParameter('recapitulatif');
  	$indice = $request->getParameter('indice');
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
  }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeConfirmation(sfWebRequest $request)
  {
  	$this->forward404Unless($this->contrat = $this->getUser()->getContrat());
  	$this->form = new CompteModificationEmailForm($this->contrat);
  	$this->showForm = false;
    if ($request->isMethod(sfWebRequest::POST)) {
        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
           $this->form->save();
           $this->getUser()->setFlash('success', 'Modification prise en compte, vous devriez recevoir un nouvel email');
           $this->redirect('@contrat_etablissement_confirmation');
        }
        else {
        	$this->showForm = true;
        }
    }
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
