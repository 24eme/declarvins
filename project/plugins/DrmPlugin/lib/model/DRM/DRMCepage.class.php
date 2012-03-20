<?php
/**
 * Model for DRMCepage
 *
 */

class DRMCepage extends BaseDRMCepage {
	/**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
    	
        return $this->getParent()->getParent();
    }

    public function sommeLignes($lines) {
      $sum = 0;
      foreach($this->millesimes as $mille) {
	$sum += $mille->sommeLignes($lines);
      }
      return $sum;
    }
	
}