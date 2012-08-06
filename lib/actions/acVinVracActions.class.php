<?php
class acVinVracActions extends sfActions
{
	public function init()
	{
		$this->interpro = $this->getInterpro();
		$this->interpro_name = strtolower($this->getInterproLibelle($this->interpro->_id));
		$this->configurationVrac = $this->getConfigurationVrac($this->interpro->_id);
		$this->configurationVracEtapes = $this->configurationVrac->getEtapes();
	}
	
	public function executeIndex(sfWebRequest $request)
	{
		$this->vracs = VracHistoryView::getInstance()->retrieveLastDocs();
	}

	public function executeNouveau(sfWebRequest $request)
	{
		$this->init();
		$vrac = new Vrac();
		$vrac->numero_contrat = $this->getNumeroContrat();
		$vrac->save();
		$this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $vrac, 'step' => $this->configurationVracEtapes->next($vrac->etape)));
	}

	public function executeEtape(sfWebRequest $request)
	{
		$this->forward404Unless($this->etape = $request->getParameter('step'));
		$this->init();
		$this->vrac = $this->getRoute()->getVrac();
		$this->vrac->setEtape($this->etape);
		$this->form = $this->getForm($this->interpro->_id, $this->etape, $this->configurationVrac, $this->vrac);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$vrac = $this->form->save();

				if (!$this->configurationVracEtapes->next($vrac->etape)) {
					$this->redirectAfterEtapes($vrac);
				} else {
					/*
					 * @todo Rendre propre cette condition
					 */
					if (!$vrac->has_transaction && $this->configurationVracEtapes->next($vrac->etape) == 'transaction') {
						$this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $vrac, 'step' => $this->configurationVracEtapes->next('transaction')));
					} else {
						$this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $vrac, 'step' => $this->configurationVracEtapes->next($vrac->etape)));
					}
				}
			}
		}
	}
	public function executeRecapitulatif(sfWebRequest $request)
	{
		$this->init();
		$this->vrac = $this->getRoute()->getVrac();
	}
	
	public function getForm($interproId, $etape, $configurationVrac, $vrac)
	{
		return VracFormFactory::create($etape, $configurationVrac, $vrac);
	}
	
	public function redirectAfterEtapes($object)
	{
		$this->redirect('vrac_termine', $object);
	}
	
	public function getNumeroContrat()
	{
		return VracClient::getInstance()->getNextNoContrat();
	}
	
	public function getInterproLibelle($interpro_id = null)
	{
		return ($interpro_id)? str_replace('INTERPRO-', '', $interpro_id) : '';
	}
	
	public function getInterpro()
	{
		return $this->getUser()->getInterpro();
	}
	
	public function getConfigurationVrac($interpro_id = null)
	{
		return ConfigurationClient::getCurrent()->getConfigurationVracByInterpro($interpro_id);
	}
}