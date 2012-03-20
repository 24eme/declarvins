<?php
/**
 * Model for DRMDroit
 *
 */


class DRMDroit extends BaseDRMDroit {
  public function setTaux($taux) {
    if ($taux <= 0) {
      return;
    }
    return $this->_set('taux', $taux);
  }

  public function integreVolume($volume_taxable, $volume_reintegre, $taux) {
    if (!$this->taux) {
      $this->taux = $taux;
    }
    if (!$this->code) {
      $this->code = $this->key;
    }
    $this->volume_taxe += $volume_taxable;
    $this->volume_reintegre += $volume_reintegre;
  }
  public function getPayable() {
    return ($this->volume_taxe - $this->volume_reintegre) * $this->taux;
  }
                
}