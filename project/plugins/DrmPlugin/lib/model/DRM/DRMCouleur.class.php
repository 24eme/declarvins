<?php
/**
 * Model for DRMCouleur
 *
 */

class DRMCouleur extends BaseDRMCouleur {
    
    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {
        return $this->getParent()->getParent();
    }
    
    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->cepages as $cepage) {
	$sum += $cepage->sommeLignes($lines);
      }
      return $sum;
    }
	
}