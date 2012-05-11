<?php
/**
 * Model for DRMGenre
 *
 */

class DRMGenre extends BaseDRMGenre {

	/**
     *
     * @return DRMGenre
     */
    public function getCertification() {
        return $this->getParent()->getParent();
    }
    
    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->appellations as $appellation) {
		$sum += $appellation->sommeLignes($lines);
      }
      return $sum;
    }
}