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
    public function executeHelp(sfWebRequest $request) {
    	$this->etablissement = $this->getRoute()->getEtablissement();
    	$this->compte = $this->getUser()->getCompte();
    	if (!$this->compte->isTiers()) {
    		throw new sfError404Exception();
    	}
    	$this->form = new CielAssistanceForm();
    	if ($request->isMethod(sfWebRequest::POST)) {
    		$this->form->bind($request->getParameter($this->form->getName()));
    		if ($this->form->isValid()) {
    			$values = $this->form->getValues();
    			Email::getInstance()->sendCielAssistance($values, $this->etablissement, InterproClient::getInstance()->getById($this->etablissement->interpro));
    			$this->getUser()->setFlash('assistance', "Votre demande a bien été envoyée.");
    			$this->redirect('ciel_help', $this->etablissement);
    		}
    	}
    }

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
    	$this->etablissement = $this->getRoute()->getEtablissement();
    	$this->compte = $this->getUser()->getCompte();
    	if (!$this->compte->isTiers()) {
    		throw new sfError404Exception();
    	}
    	$this->convention = $this->compte->getConventionCiel();
    }

    /**
     * 
     *
     * @param sfRequest $request A request object
     */
    public function executeNouveau(sfWebRequest $request) 
    {
    	$this->etablissement = $this->getRoute()->getEtablissement();
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
                $this->redirect('convention_habilitations', $this->etablissement);
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
  	$this->etablissement = $this->getRoute()->getEtablissement();
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
                $this->redirect('convention_recapitulatif', $this->etablissement);
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
  	$this->etablissement = $this->getRoute()->getEtablissement();
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
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->getUser()->getCompte();
  	if (!$this->compte->isTiers()) {
  		throw new sfError404Exception();
  	}
  	$this->convention = $this->compte->getConventionCiel();
  	$this->forward404Unless($this->convention);
  	
  	if (!$this->convention->valide) {
  		$this->convention->valide = 1;
  		$this->convention->save();
  		$this->generatePdf();
  		$this->generateAvenant();
    	Email::getInstance()->sendConventionCiel($this->convention, $this->compte->email, array(InterproClient::getInstance()->getById($this->convention->interpro)), ContratClient::getInstance()->find($this->compte->contrat));
    	Email::getInstance()->sendConventionCiel($this->convention, $convention->getEmailInterprofession(), array(InterproClient::getInstance()->getById($this->convention->interpro)), ContratClient::getInstance()->find($this->compte->contrat));
  	}

  }

  /**
   *
   *
   * @param sfRequest $request A request object
   */
  public function executeResend(sfWebRequest $request)
  {
	  	$rows = acCouchdbManager::getClient()
	  	->getView("convention", "inscription")
	  	->rows;
  	 
	  	foreach($rows as $row) {
	  		if ($compte = _CompteClient::getInstance()->find(str_replace("CONVENTIONCIEL-", "COMPTE-", $row->id))) {
  				$convention = $compte->getConventionCiel();
	  			$this->generatePdf($compte);
	  			$this->generateAvenant($compte);
	  			echo $compte->email." ".$convention->getEmailInterprofession()."<br />";
	  			Email::getInstance()->sendConventionCiel($this->convention, $compte->email, array(InterproClient::getInstance()->getById($convention->interpro)), ContratClient::getInstance()->find($compte->contrat));
	  			Email::getInstance()->sendConventionCiel($this->convention, $convention->getEmailInterprofession(), array(InterproClient::getInstance()->getById($convention->interpro)), ContratClient::getInstance()->find($compte->contrat));
	  			Email::getInstance()->sendConventionCiel($this->convention, "jblemetayer@actualys.com", array(InterproClient::getInstance()->getById($convention->interpro)), ContratClient::getInstance()->find($compte->contrat));
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
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->getUser()->getCompte();
  	if (!$this->compte->isTiers()) {
  		throw new sfError404Exception();
  	}
  	$this->convention = $this->compte->getConventionCiel();
  	$this->forward404Unless($this->convention);
  	
  	$this->generatePdf();
  	$path = sfConfig::get('sf_data_dir').'/convention-ciel';
	$response = $this->getResponse();
  	$response->setHttpHeader('Content-Type', 'application/pdf');
  	$response->setHttpHeader('Content-disposition', 'attachment; filename="' . basename($path.'/pdf/'.$this->convention->_id.'.pdf') . '"');
  	$response->setHttpHeader('Content-Length', filesize($path.'/pdf/'.$this->convention->_id.'.pdf'));
  	$response->setHttpHeader('Pragma', '');
  	$response->setHttpHeader('Cache-Control', 'public');
  	$response->setHttpHeader('Expires', '0');
  	
  	return $this->renderText(file_get_contents($path.'/pdf/'.$this->convention->_id.'.pdf'));
  }
  

  public function executeAvenant(sfWebRequest $request)
  {
  	$this->etablissement = $this->getRoute()->getEtablissement();
  	$this->compte = $this->getUser()->getCompte();
  	if (!$this->compte->isTiers()) {
  		throw new sfError404Exception();
  	}
  	$this->convention = $this->compte->getConventionCiel();
  	$this->forward404Unless($this->convention);
  	$this->contrat = ContratClient::getInstance()->find($this->compte->contrat);
  	$pdf = new ExportAvenantPdf($this->contrat);
  	return $this->renderText($pdf->render($this->getResponse(), false));
  }
  
  protected function generatePdf($c = null) 
  {
  	$compte = ($c)? $c : $this->getUser()->getCompte();
	if ($convention = $compte->getConventionCiel()) {
		$path = sfConfig::get('sf_data_dir').'/convention-ciel';
		if (!file_exists($path.'/pdf/'.$convention->_id.'.pdf')) {
			$fdf = tempnam(sys_get_temp_dir(), 'CONVENTIONCIEL');
			file_put_contents($fdf, $convention->generateFdf());
			exec("pdftk ".$path."/template.pdf fill_form $fdf output  /dev/stdout flatten |  gs -o ".$path.'/pdf/'.$convention->_id.".pdf -sDEVICE=pdfwrite -dEmbedAllFonts=true  -sFONTPATH=\"/usr/share/fonts/truetype/freefont\" - ");
			unlink($fdf);
		}
	}
  }
  
  protected function generateAvenant($c = null) 
  {
  	$compte = ($c)? $c : $this->getUser()->getCompte();
	if ($convention = $compte->getConventionCiel()) {
		$contrat = ContratClient::getInstance()->find($compte->contrat);
		$pdf = new ExportAvenantPdf($contrat);
  		$pdf->generate();
	}
  }
}
