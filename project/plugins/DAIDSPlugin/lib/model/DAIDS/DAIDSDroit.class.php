<?php
/**
 * Model for DAIDSDroit
 *
 */


class DAIDSDroit extends BaseDAIDSDroit 
{
  private $virtual = 0;
  private $payable_total = 0;

  /*public function setTaux($taux) {
    if ($taux <= 0) {
      return;
    }
    return $this->_set('taux', $taux);
  }*/

  public function integreVirtualVolume($droit) 
  {
    $this->virtual = 1;
    $this->integreVolume($droit->volume_taxe, '', '');
    $this->payable_total += $droit->getPayable();
  }

  public function integreVolume($volume_taxable, $taux, $libelle) 
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
  	$this->total = $this->volume_taxe * $this->taux;
  }

  public function updateTotal($total) 
  {
  	$this->total += $total;
  }
  
  public function getPayable() 
  {
    if ($this->virtual) {
      return $this->payable_total;
    }
    return $this->total;
  }

  public function getLibelle() 
  {
	$l = $this->_get('libelle');
	if  (!$l) {
		$l = $this->getCode();
	}
	return $l;
  }

  public function isTotal() 
  {
   	if ($this->virtual) {
		return true;
   	}
    if (preg_match('/^(.*)_/', $this->code, $m) && $this->parent->exist($m[1])) {
		return false;
    }
    return true;
  }
  
  public function isVirtual() 
  {
    return ($this->virtual);
  }

  public function isDroit($type) 
  {
  	return ($this->getParent()->getKey() == $type)? true : false;
  }

  public function getTaux() 
  {
	$t = $this->_get('taux');
	if ($t) {
		return $t;
	}
	return 0.0;
  }
}
