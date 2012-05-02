<?php
/**
 * Model for DRMProduits
 *
 */

class DRMProduits extends BaseDRMProduits {
	
	public function hasMouvement() {
		
		foreach($this as $certification) {
			if ($certification->hasMouvement()) {

				return true;
			}
		}

		return false;
	}
	
}