<?php
class DRMDroits extends BaseDRMDroits 
{
	const DROIT_CVO = 'cvo';
	const DROIT_DOUANE = 'douane';

	static $droit_entrees = array('entrees/crd');
	public static function getDroitEntrees($merge = array()) 
	{
    	return array_merge(self::$droit_entrees, $merge);
  	}
  	
  	static $droit_entrees_inter_rhone = array();
  	public static function getDroitEntreesInterRhone() 
  	{
    	return self::$droit_entrees_inter_rhone;
  	}
  	
  	static $droit_sorties = array('sorties/vrac', 'sorties/export', 'sorties/factures', 'sorties/crd');
  	public static function getDroitSorties($merge = array()) 
  	{
    	return array_merge(self::$droit_sorties, $merge);
  	}
  	
  	static $droit_sorties_inter_rhone = array();
  	public static function getDroitSortiesInterRhone() 
  	{
    	return self::$droit_sorties_inter_rhone;
  	}
  	
  	static $douane_droit_sorties = array('sorties/factures', 'sorties/crd');
  	public static function getDouaneDroitSorties() 
  	{
    	return self::$douane_droit_sorties;
  	}

	private $res = array();
	
	private function addVirtual($key, $value) 
	{
    	if (!isset($this->res[$key])) {
      		$this->res[$key] = new DRMDroit($value->getDefinition(), $value->getDocument(), $value->getHash());
    	}
    	$this->res[$key]->integreVirtualVolume($value);
    	$this->res[$key]->code = $key;
    	$this->res[$key]->libelle = $value->libelle;
  	}

	public function getDroitsWithVirtual() 
	{
    	$this->res = $this->toArray();
    	$nb_total = array();
    	foreach($this->res as $key => $value) {
	    	/*if (preg_match('/^([^_]+)_/', $key, $m)) {
				$this->addVirtual($m[1], $value);
				if (!isset($nb_total[$m[1]])) {
					$nb_total[$m[1]] = 1;
				} else {
	        		$nb_total[$m[1]]++;
				}
	      	}*/
      		$this->addVirtual('ZZZZTotal', $value);
    	}
    
    	foreach($nb_total as $code => $nb) {
			if ($nb < 2) {
				unset($this->res[$code]);
			}
    	}
    	
    	if (count($this->res) == 2) {
      		unset($this->res['ZZZZTotal']);
    	}
    	krsort($this->res);
    	if (isset($this->res['ZZZZTotal'])) {
			$this->res['Total'] = $this->res['ZZZZTotal'];
			unset(	$this->res['ZZZZTotal'] );
			$this->res['Total']->code = 'Total';
			$this->res['Total']->libelle	 = 'Total HT';
    	}
    	return $this->res;
  	}

	public function getCumul() 
	{
    	$sum = 0;
    	foreach ($this->toArray() as $key => $value) {
      		$sum += $value->getCumul();
    	}
    	return $sum;
  	}

	public function getReport() 
	{
    	$sum = 0;
    	foreach ($this->toArray() as $key => $value) {
      		$sum += $value->getReport();
    	}
    	return $sum;
  	}
}
