<?php
class DRMValidation
{
	private $drm;
	private $engagements;
	private $warnings;
	private $errors;
	
	public function __construct($drm)
	{
		$this->drm = $drm;
		$this->engagements = array();
		$this->warnings = array();
		$this->errors = array();
	}
	
	public function getEngagements()
	{
		return $this->engagements;
	}
	
	public function getWarnings()
	{
		return $this->warnings;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	public function isValide()
	{
		foreach ($this->drm->declaration->certifications as $certification) {
			foreach ($certification->appellations as $appellation) {
				foreach ($appellation->couleurs as $couleur) {
					foreach ($couleur->cepages as $cepage) {
						foreach ($cepage->millesimes as $millesime) {
							foreach($millesime->details as $detail) {
								$this->controleEngagements($detail);
								$this->controleErrors($detail);
								$this->controleWarnings($detail);
							}
						}
					}
				}
			}	
		}
		return ($this->hasEngagements() && $this->hasErrors() && $this->hasWarnings());
	}
	
	private function controleEngagements($detail)
	{
		if ($detail->sorties->export > 0) {
			$this->engagements[] = new DRMControleEngagement('export');
		}
		if ($detail->sorties->declassement > 0) {
			$this->engagements[] = new DRMControleEngagement('declassement');
		}
		if ($detail->sorties->repli > 0) {
			$this->engagements[] = new DRMControleEngagement('repli');
		}
	}
	
	private function controleErrors($detail)
	{
		$totalVolume = 0;
		foreach ($detail->vrac as $contrat) {
			$totalVolume += $contrat->volume;
		}
		if ($totalVolume != $detail->sorties->vrac) {
			$this->errors[] = new DRMControleError('vrac', $this->generateUrl('vrac'));
		}
		if ($detail->total < 0) {
			$this->errors[] = new DRMControleError('total_negatif', $this->generateUrl('drm_recap', array('sf_subject' => $detail->getAppellation())));
		}
		if ($detail->total < ($detail->stocks->bloque + $detail->stocks->instance)) {
			$this->errors[] = new DRMControleError('total_stocks', $this->generateUrl('drm_recap', array('sf_subject' => $detail->getAppellation())));
		}
	}
	
	private function controleWarnings($detail)
	{
		if ($detail->sorties->mouvement > 0) {
			$this->warnings[] = new DRMControleWarning('mouvement', $this->generateUrl('drm_recap', array('sf_subject' => $detail->getAppellation())));
		}
	}
	
	public function hasEngagements()
	{
		return (count($this->engagements) > 0)? true : false;
	}
	
	public function hasErrors()
	{
		return (count($this->errors) > 0)? true : false;
	}
	
	public function hasWarnings()
	{
		return (count($this->warnings) > 0)? true : false;
	}
	
	public function generateUrl($route, $params = array(), $absolute = false)
	{
		return sfContext::getInstance()->getRouting()->generate($route, $params, $absolute);
	}
	
}