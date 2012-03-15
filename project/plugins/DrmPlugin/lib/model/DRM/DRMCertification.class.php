<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {
    
	public function getProduits() {
		return $this->getDocument()->produits->get($this->getKey());
	}

}