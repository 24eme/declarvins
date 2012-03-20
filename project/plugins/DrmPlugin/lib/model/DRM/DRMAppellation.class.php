<?php
/**
 * Model for DRMCertification
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMCertification
     */
    public function getCertification() {
        return $this->getParent()->getParent();
    }
    
    public function getProduits() {
      return $this->getDocument()->produits->get($this->getCertification()->getKey())->get($this->getKey());
    }

    public function updateDroits($droits) {
      foreach ($this->getDroits() as $typedroits => $droit) {
	$droits->add($typedroits)->add($droit->code)->integreVolume($this->sommeLignes(array('total_sorties')), $this->sommeLignes(array('entrees/crd')), $droit->taux);
      }
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->lieux as $lieu) {
	$sum += $lieu->sommeLignes($lines);
      }
      return $sum;
    }
    
    public function getDroits() {
      $conf = $this->getConfig();
      $droits = array();
      foreach ($conf->getDroits($this->getInterproKey()) as $key => $droit) {
	$droits[$key] = $droit->getCurrentDroit($this->getCampagne());
      }
      return $droits;
    }
    public function getInterproKey() {
      return $this->getDocument()->getInterpro()->get('_id');
    }
    public function getCampagne() {
      return $this->getDocument()->getCampagne();
    }
}