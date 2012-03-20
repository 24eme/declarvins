<?php
/**
 * Model for DRMLieu
 *
 */

class DRMLieu extends BaseDRMLieu {

	/**
     *
     * @return DRMLieu
     */
    public function getAppellation() {
        return $this->getParent()->getParent();
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->couleurs as $couleur) {
	$sum += $couleur->sommeLignes($lines);
      }
      return $sum;
    }
	
}