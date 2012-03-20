<?php
/**
 * Model for DRMDroit
 *
 */


class DRMDroit extends BaseDRMDroit {
  private $virtual = 0;
  private $payable_total = 0;
  private $libelles = array('VDN' => 'AOC VDN', 'VM' => 'Vins mousseux', 'VT' => 'Vins Tranquilles', 'VT_AOP' => 'Vins Tranquilles / AOC', 'VT_IGP' => 'Vins Tranquilles / IGP', 'VT_SSIG' => 'Vins Tranquilles / Sans IG');

  public function setTaux($taux) {
    if ($taux <= 0) {
      return;
    }
    return $this->_set('taux', $taux);
  }

  public function integreVirtualVolume($drmdroit) {
    $this->virtual = 1;
    $this->integreVolume($drmdroit->volume_taxe, $drmdroit->volume_reintegre, '');
    $this->payable_total += $drmdroit->getPayable();
  }

  public function integreVolume($volume_taxable, $volume_reintegre, $taux) {
    if (!$this->taux && $taux) {
      $this->taux = $taux;
    }
    if (!$this->code) {
      $this->code = $this->key;
    }
    $this->volume_taxe += $volume_taxable;
    $this->volume_reintegre += $volume_reintegre;
  }
  public function getPayable() {
    if ($this->virtual)
      return $this->payable_total;
    return ($this->volume_taxe - $this->volume_reintegre) * $this->taux;
  }
  public function isTotal() {
    return ($this->virtual);
  }
  public function getLibelle() {
    return isset($this->libelles[$this->code]) ? $this->libelles[$this->code] : $this->code;
  }
}