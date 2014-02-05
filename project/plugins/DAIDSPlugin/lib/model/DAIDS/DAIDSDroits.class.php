<?php
class DAIDSDroits extends BaseDAIDSDroits 
{
  const DROIT_CVO = 'cvo';
  const DROIT_DOUANE = 'douane';
  private $res = array();
  
  private function addVirtual($key, $value) 
  {
	if (!isset($this->res[$key])) {
      $this->res[$key] = new DAIDSDroit($value->getDefinition(), $value->getDocument(), $value->getHash());
    }
    $this->res[$key]->integreVirtualVolume($value);
    $this->res[$key]->code = $key;
    $this->res[$key]->libelle = $value->libelle;
  }

  public function getDroitsWithVirtual() 
  {
    $this->res = $this->toArray();
    $nb_total = array();
    foreach($this->toArray() as $key => $value) {
		if (preg_match('/^([^_]+)_/', $key, $m)) {
			$this->addVirtual($m[1], $value);
			if (!isset($nb_total[$m[1]])) {
				$nb_total[$m[1]] = 1;
			} else {
        		$nb_total[$m[1]]++;
			}
      	}
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
		unset($this->res['ZZZZTotal']);
		$this->res['Total']->code = 'Total';
		$this->res['Total']->libelle	 = 'Total HT';
    }
    return $this->res;
  }
}
