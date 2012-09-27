<?php
class acVinVracActions extends sfActions
{
	public function init($etablissement)
	{
		$this->interpro = $this->getInterpro($etablissement);
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
		$this->configurationVracEtapes = $this->configurationVrac->getEtapes();
	}
	
	public function executeIndex(sfWebRequest $request)
    {
        $this->etablissement = null;
        $this->vracs = VracHistoryView::getInstance()->findLast();
    }

    public function executeEtablissement(sfWebRequest $request)
	{
        $this->etablissement = $this->getRoute()->getEtablissement();
		$this->vracs = VracSoussigneIdentifiantView::getInstance()->findByEtablissement($this->etablissement->identifiant);

        $this->setTemplate('index');
	}

	public function executeNouveau(sfWebRequest $request)
	{
        $this->etablissement = $this->getRoute()->getEtablissement();
		$this->init($this->etablissement);
		$vrac = new Vrac();
		$vrac->numero_contrat = $this->getNumeroContrat();
		$vrac->save();
		$this->redirect(array('sf_route' => 'vrac_etape', 
                              'sf_subject' => $vrac, 
                              'step' => $this->configurationVracEtapes->next($vrac->etape), 
                              'etablissement' => $this->etablissement));
	}

	public function executeSupprimer(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->vrac->delete();
		$this->redirect('vrac_etablissement', array('sf_subject' => $this->etablissement));
	}

	public function executeStatut(sfWebRequest $request)
	{
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        if ($this->statut = $request->getParameter('statut')) {
        	$statuts = VracClient::getInstance()->getStatusContrat();
        	if (in_array($this->statut, $statuts)) {
        		$statut_credentials = VracClient::getInstance()->getStatusContratCredentials();
        		$statut_credentials = $statut_credentials[$this->vrac->valide->statut];
        		if (in_array($this->statut, $statut_credentials)) {
        			$this->vrac->valide->statut = $this->statut;
        			$this->vrac->save();
					$this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
        		}
        	} else {
        		throw new sfException('Unknown status');	
        	}
        } else {
        	throw new sfException('Status needed');
        }
	}

	public function executeEtape(sfWebRequest $request)
	{
		$this->forward404Unless($this->etape = $request->getParameter('step'));
        $this->etablissement = $this->getRoute()->getEtablissement();
		$this->init($this->etablissement);
        $this->vrac = $this->getRoute()->getVrac();
        if ($this->vrac->isValide()) {
        	throw new sfException('Le contrat vrac n°'.$this->vrac->numero_contrat.' est validé');
        }
        if ($this->vrac->valide->statut && $this->vrac->valide->statut != VracClient::STATUS_CONTRAT_NONSOLDE) {
        	throw new sfException('Ce contrat ne peut pas être modifié - statut '.$this->vrac->valide->statut);
        }
		$this->vrac->setEtape($this->etape);
		$this->form = $this->getForm($this->interpro->_id, $this->etape, $this->configurationVrac, $this->etablissement, $this->vrac);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$this->form->save();

				if (!$this->configurationVracEtapes->next($this->vrac->etape)) {
                    $this->getUser()->setFlash('termine', true);
			        return $this->redirect('vrac_visualisation', array('sf_subject' => $this->vrac, 'etablissement' => $this->etablissement));
				}

				if (!$this->vrac->has_transaction && $this->configurationVracEtapes->next($this->vrac->etape) == 'transaction') {
					
                    return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => $this->configurationVracEtapes->next('transaction'), 'etablissement' => $this->etablissement));
				}
				
                return $this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $this->vrac, 'step' => $this->configurationVracEtapes->next($this->vrac->etape), 'etablissement' => $this->etablissement));
			}
		}
	}


  public function executeSetEtablissementInformations(sfWebRequest $request)
  {
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();   
        $this->soussigne = $request->getParameter('soussigne', null);
        $this->type = $request->getParameter('type', null);
        $this->etape = $request->getParameter('step', null);

        if (!$this->soussigne) {

        	throw new sfException('Numéro d\'établissement du soussigne requis');
        }

        if (!$this->type) {

        	throw new sfException('Type requis');
        }

        if (!$this->etape) {

        	throw new sfException('Etape requis');
        }

        $this->init($this->etablissement);
        $this->soussigne = EtablissementClient::getInstance()->find($this->soussigne);
        if (!$this->vrac->exist($this->type)) {

            throw new sfException('Type '.$this->type.' n\'existe pas');
        }
  	
        $this->vrac->storeSoussigneInformations($this->type, $this->soussigne);
		$this->form = $this->getForm($this->interpro->_id, $this->etape, $this->configurationVrac, $this->etablissement, $this->vrac);
		
        if ($this->type != 'mandataire') {
			return $this->renderPartial('form_etablissement', array('form' => $this->form[$this->type]));
		}
		
    	return $this->renderPartial('form_mandataire', array('form' => $this->form[$this->type]));	
  }
  
  public function executePdf(sfWebRequest $request)
  {
    	ini_set('memory_limit', '512M');
    	$this->interpro = $this->getInterpro($this->getRoute()->getEtablissement());
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
  		$pdf = new ExportVracPdf($this->vrac, $this->configurationVrac);
    	return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
  public function executePdfTransaction(sfWebRequest $request)
  {
    	ini_set('memory_limit', '512M');
    	$this->interpro = $this->getInterpro($this->getRoute()->getEtablissement());
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
  		$pdf = new ExportVracPdfTransaction($this->vrac, $this->configurationVrac);
    	return $this->renderText($pdf->render($this->getResponse(), false, $request->getParameter('format')));
  }
  
  public function executeGetCvo(sfWebRequest $request)
  {
        $this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();   
        $this->hash = $request->getParameter('hash', null);
        if (!$this->hash) {

        	throw new sfException('Hash produit requis');
        }
        $this->hash = str_replace('-', '/', $this->hash);
        $result = ConfigurationClient::getInstance()->findDroitsByHash($this->hash)->rows;
        if (count($result) == 0) {
        	throw new sfException('Aucun résultat pour le produit '.$this->hash);
        }
        $result = $result[0];
        $droits = $result->value;
        $cvo = $droits->cvo;
        echo $cvo->taux;
		return sfView::NONE;
  }
	public function executeVisualisation(sfWebRequest $request)
	{
		$this->vrac = $this->getRoute()->getVrac();
        $this->etablissement = $this->getRoute()->getEtablissement();
        $this->init($this->etablissement);
	}

	public function getForm($interproId, $etape, $configurationVrac, $etablissement, $vrac)
	{
		return VracFormFactory::create($etape, $configurationVrac, $etablissement, $vrac);
	}
	
	public function getNumeroContrat()
	{
		return VracClient::getInstance()->getNextNoContrat();
	}
	
	public function getInterpro($etablissement)
	{
        if($etablissement) {
            
            return $etablissement->getInterproObject();
        }
		
        return $this->getUser()->getInterpro();
	}
	
	public function getConfigurationVrac($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro_id);
	}
}