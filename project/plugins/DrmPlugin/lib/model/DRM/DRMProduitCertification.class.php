<?php
/**
 * Model for DRMProduitCertification
 *
 */

class DRMProduitCertification extends BaseDRMProduitCertification {

	public function getDeclaration() {

		return $this->getDocument()->declaration->certifications->get($this->getKey());
	}

	public function hasMouvement() {
		
		foreach($this as $appellation) {
			if ($appellation->hasMouvement()) {

				return true;
			}
		}

		return false;
	}	
}