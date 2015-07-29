<?php
/**
 * Model for DRMDroit
 *
 */


class DRMDroit extends BaseDRMDroit 
{
  	private $virtual = 0;
  	private $payable_total = 0;
  	private $cumulable_total = 0;
  	private $reportable_total = 0;

  	public function setTaux($taux) 
  	{
    	if ($taux <= 0) {
      		return;
    	}
    	return $this->_set('taux', $taux);
  	}

  	public function integreVirtualVolume($drmdroit) 
  	{
    	$this->virtual = 1;
    	$this->integreVolume($drmdroit->volume_taxe, $drmdroit->volume_reintegre, '', $drmdroit->report, '');
    	$this->payable_total += $drmdroit->getPayable();
    	$this->cumulable_total += $drmdroit->getCumulable();
    	$this->reportable_total += $drmdroit->getReportable();
  	}

  	public function integreVolume($volume_taxable, $volume_reintegre, $taux, $report, $libelle, $negatif = false) 
  	{
	  	if (!$this->libelle && $libelle) {
	      $this->libelle = $libelle;
	    }
	    if (!$this->taux && $taux) {
	      $this->taux = $taux;
	    }
	    if (!$this->code) {
	      $this->code = $this->key;
	    }
	    $this->volume_taxe += $volume_taxable;
	    $this->volume_reintegre += $volume_reintegre;
	  	$this->total = ($this->volume_taxe - $this->volume_reintegre) * $this->taux;
	  	if (!$negatif && $this->total < 0) {
	  		$this->total = 0;
	  	}
	  	if ($this->isReportable()) {
	    	$this->report = $report;
	  		$this->cumul = $this->total + $this->report;
	  	}
	}
  
	public function getPayable() 
	{
		return ($this->virtual)? $this->payable_total : $this->total;
	}
	
  	public function getCumulable() 
  	{
  		return ($this->virtual)? $this->cumulable_total : $this->cumul;
  	}
	
  	public function getReportable() 
  	{
  		return ($this->virtual)? $this->reportable_total : $this->report;
  	}

  	public function getLibelle() 
  	{
		$l = $this->_get('libelle');
		return ($l)? $l : $this->getCode();
  	}

	public function isTotal() 
	{
   		if ($this->virtual || $this->getKey() == 'Total') {
			return true;
   		}
    	return false;
  	}
  
  	public function isVirtual() 
  	{
    	return ($this->virtual);
  	}
  	
  	public function isReportable() 
  	{
  		return ($this->getPaiement()->isAnnuelle() && $this->isDroit('douane'))? true : false;
  	}
  	
  	public function isDroit($type) 
  	{
  		return ($this->getParent()->getKey() == $type)? true : false;
  	}
  	
  	public function getPaiement() 
  	{
  		return $this->getDocument()->get('declaratif')->get('paiement')->get($this->getParent()->getKey());
  	}
  	
  	public function getTaux() 
  	{
		$t = $this->_get('taux');
		return ($t)? $t : 0.0;
  	}
}
