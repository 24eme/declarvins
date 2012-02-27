<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {
    
	public function getProduitsCertification() {
		return $this->getDocument()->produits->get($this->getKey());
	}

}