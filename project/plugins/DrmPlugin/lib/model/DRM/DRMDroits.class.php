<?php
class DRMDroits extends BaseDRMDroits {
  private $res = array();
  private function addVirtual($key, $value) {
    if (!isset($this->res[$key]))
      $this->res[$key] = new DRMDroit($value->getDefinition(), $value->getDocument(), $value->getHash());
    $this->res[$key]->integreVirtualVolume($value);
    $this->res[$key]->code = $key;
  }

  public function getDroitsWithVirtual() {
    $this->res = $this->toArray();
    foreach($this->toArray() as $key => $value) {
      if (preg_match('/^([^_]+)_/', $key, $m)) {
	$this->addVirtual($m[1], $value);
      }
      $this->addVirtual('ZZZZTotal', $value);
    }
    if (count($this->res) == 2) {
      unset($this->res['ZZZZTotal']);
    }
    if (isset($this->res['ZZZZTotal'])) {
	$this->res['Total'] = $this->res['ZZZZTotal'];
	unset(	$this->res['ZZZZTotal'] );
	$this->res['Total']->code = 'Total';
    }
    return $this->res;
  }
}