<?php

/**
 * conventionCiel actions.
 *
 * @package    declarvin
 * @subpackage contrat
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class conventionCielActions extends sfActions
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
    public function executeNouveau(sfWebRequest $request) 
    {
    	$this->compte = $this->getUser()->getCompte();
    	if (!$this->compte->isTiers()) {
    		throw new sfError404Exception();
    	}
    	$this->convention = $this->compte->getConventionCiel();
    	
    	if (!$this->convention) {
    		$this->convention = ConventionCielClient::getInstance()->createObject($this->compte);
    	} elseif ($this->convention->valide) {
    		$this->redirect('tiers');
    	}
    	
    	
        $this->form = new ConventionCielForm($this->convention);
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('convention_habilitations');
            }
        }
    }
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeHabilitations(sfWebRequest $request)
  {
  	$this->compte = $this->getUser()->getCompte();
  	if (!$this->compte->isTiers()) {
  		throw new sfError404Exception();
  	}
  	$this->convention = $this->compte->getConventionCiel();
  	$this->forward404Unless($this->convention);
  	
  	$nbHabilitation = (count($this->convention->habilitations) > 0)? count($this->convention->habilitations) : 1;
  	$this->nbHabilitation = $request->getParameter('nb_habilitation', $nbHabilitation);
    	
    	
        $this->form = new ConventionCielHabilitationsForm($this->convention, array('nbHabilitation' => $this->nbHabilitation));
        if ($request->isMethod(sfWebRequest::POST)) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->redirect('convention_recapitulatif');
            }
        }
  	

  }
 
 /**
  * 
  *
  * @param sfRequest $request A request object
  */
  public function executeRecapitulatif(sfWebRequest $request)
  {
  	$this->compte = $this->getUser()->getCompte();
  	if (!$this->compte->isTiers()) {
  		throw new sfError404Exception();
  	}
  	$this->convention = $this->compte->getConventionCiel();
  	$this->forward404Unless($this->convention);
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
