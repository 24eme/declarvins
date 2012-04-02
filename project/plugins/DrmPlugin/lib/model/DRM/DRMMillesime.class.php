<?php
/**
 * Model for DRMMillesime
 *
 */

class DRMMillesime extends BaseDRMMillesime {

	public function getCepage() {
		
		return $this->getParent()->getParent();
	}

	public function sommeLignes($lines) {
	  $sum = 0;
	  foreach($this->details as $detail) {
	    $sum += $detail->sommeLignes($lines);
	  }
	  return $sum;
	}
	
}