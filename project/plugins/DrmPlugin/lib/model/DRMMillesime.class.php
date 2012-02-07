<?php
/**
 * Model for DRMMillesime
 *
 */

class DRMMillesime extends BaseDRMMillesime {

	public function getCepage() {
		return $this->getParent()->getParent();
	}

}