<?php
/**
 * vrac actions.
 *
 * @package    vinsdeloire
 * @subpackage vrac
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class vracActions extends sfActions
{
	public function init()
	{
		$this->interpro = $this->getInterpro();
		$this->interpro_name = $this->getInterproLibelle($this->interpro->_id);
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
		$this->form = VracFormFactory::create($this->interpro->_id, $this->etape, $this->configurationVrac, $this->vrac);
		if ($request->isMethod(sfWebRequest::POST)) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$vrac = $this->form->save();
				$this->redirect(array('sf_route' => 'vrac_etape', 'sf_subject' => $vrac, 'step' => $this->configurationVracEtapes->next($vrac->etape)));
			}
		}
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
	
	/*
	 * BAZAR A MATHURIN -->
	 */
	public function executeRecherche(sfWebRequest $request) {
		$this->redirect('vrac_recherche_soussigne', array('identifiant' => $request->getParameter('identifiant')));
	}

	public function executeRechercheSoussigne(sfWebRequest $request) {
		$this->identifiant = $request->getParameter('identifiant');
		$this->vracs = VracClient::getInstance()->retrieveBySoussigne('ETABLISSEMENT-'.$this->identifiant);
	}

	public function executeValidation(sfWebRequest $request)
	{
		$this->getResponse()->setTitle(sprintf('Contrat N° %d - Validation', $request["numero_contrat"]));
		$this->vrac = $this->getRoute()->getVrac();
		$config = ConfigurationClient::getCurrent();
		if ($request->isMethod(sfWebRequest::POST))
		{
			$this->maj_etape(4);
			$this->maj_valide(null,null,$config->getVracStatutNonSolde());
			$this->vrac->save();
			$this->redirect('vrac_termine', $this->vrac);
		}
	}


	public function executeRecapitulatif(sfWebRequest $request)
	{
		$this->getResponse()->setTitle(sprintf('Contrat N° %d - Récapitulation', $request["numero_contrat"]));
		$this->vrac = $this->getRoute()->getVrac();
		if ($request->isMethod(sfWebRequest::POST))
		{
			$this->redirect('vrac_soussigne');
		}
	}

	public function executeGetInformations(sfWebRequest $request)
	{
		$etablissement =  EtablissementClient::getInstance()->find($request->getParameter('id'));
		$nouveau = is_null($request->getParameter('numero_contrat'));
		return $this->renderPartialInformations($etablissement,$nouveau);
	}

	public function executeGetModifications(sfWebRequest $request)
	{
		$nouveau = is_null($request->getParameter('numero_contrat'));
		$etablissementId = ($request->getParameter('id')==null)? $request->getParameter('vrac_'.$request->getParameter('type').'_identifiant') : $request->getParameter('id');
		$etablissement =  EtablissementClient::getInstance()->find($etablissementId);
		$this->form = new VracSoussigneModificationForm($etablissement);

		if ($request->isMethod(sfWebRequest::POST))
		{
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid())
			{
				$this->form->save();
				return $this->renderPartialInformations($etablissement,$nouveau);
			}
		}

		$familleType = $etablissement->famille;
		if($familleType == 'Producteur' || $familleType == 'Negociant') $familleType = 'vendeurAcheteur';
		return $this->renderPartial($familleType.'Modification', array('form' => $this->form, 'type' => $request->getParameter('type')));
	}

	private function renderPartialInformations($etablissement,$nouveau) {

		$familleType = $etablissement->getFamilleType();
		return $this->renderPartial($familleType.'Informations',
		array($familleType => $etablissement, 'nouveau' => $nouveau));
	}

	private function maj_etape($etapeNum)
	{
		if($this->vrac->etape < $etapeNum) $this->vrac->etape = $etapeNum;
	}

	public function maj_valide($date_saisie = null,$identifiant = null,$status=null)
	{
		if(!$this->vrac) return;
		if(!$date_saisie) $date_saisie = date('d/m/Y');
		$this->vrac->valide->date_saisie = $date_saisie;
		$this->vrac->valide->identifiant = $identifiant;
		$this->vrac->valide->statut = $status;
	}

}
