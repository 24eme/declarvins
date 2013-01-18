<?php
class DAIDSValidation
{
	private $daids;
	private $engagements;
	private $warnings;
	private $errors;
	const VINSSANSIG_KEY = 'VINSSANSIG';
	const AOP_KEY = 'AOP';
	const IGP_KEY = 'IGP';
	const NO_LINK = '#';
	
	public function __construct($daids, $options = null)
	{
		$this->daids = $daids;
		$this->options = $options;
		$this->engagements = array();
		$this->warnings = array();
		$this->errors = array();
		$this->controleDAIDS();
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
	
	private function controleDAIDS()
	{
		foreach ($this->daids->declaration->certifications as $certification) {
			$details = $certification->getProduits();
			foreach ($details as $detail) {
				$this->controleErrors($detail);
			}
		}
	}
	
	public function isValide() {
	  return !($this->hasErrors());
	}
	
	private function controleErrors($detail)
	{
		$totalPropriete = $detail->stock_propriete;
		$totalProprieteDetails = 0;
		foreach ($detail->stock_propriete_details as $stockProprieteDetailKey => $stockProprieteDetailValue) {
			$totalProprieteDetails += $stockProprieteDetailValue;
		}
		if ($totalProprieteDetails != $totalPropriete) {
			$this->errors['total_diff_propriete_'.$detail->renderId()] = new DAIDSControleError('stock_propriete', $this->generateUrl('daids_recap_detail', $detail));
		}
		$totalChais = $detail->stocks->chais;
		$totalEntrepots = 0;
		foreach ($detail->chais_details as $entrepotsKey => $entrepotsValue) {
			$totalEntrepots += $entrepotsValue;
		}
		if ($totalEntrepots > $totalChais) {
			$this->errors['total_diff_entrepots_'.$detail->renderId()] = new DAIDSControleError('stock_entrepots', $this->generateUrl('daids_recap_detail', $detail));
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
	
	public function hasError($error)
	{
		$keys = array_keys($this->errors);
    	return (count(preg_grep('/^'.$error.'_.+$/',$keys)) > 0);
	}
	
	public function hasWarnings()
	{
		return (count($this->warnings) > 0)? true : false;
	}

	public function find($type, $identifiant)
	{
		if ($type == 'error' && array_key_exists($identifiant, $this->errors)) {

			return $this->errors[$identifiant];
		} elseif($type == 'warning' && array_key_exists($identifiant, $this->warnings)) {

			return $this->warnings[$identifiant];
		} elseif($type == 'engagement' && array_key_exists($identifiant, $this->engagements)) {
			
			return $this->engagements[$identifiant];
		}

		return null;
	}
	
	protected function generateUrl($route, $params = array(), $absolute = false)
	{
	  try {
	    return sfContext::getInstance()->getRouting()->generate($route, $params, $absolute);
	  }catch(Exception $e) {
	    return;
	  }
	}
	
}