<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {
    
	public function getProduits() {
		return $this->getDocument()->produits->get($this->getKey());
	}
	public function getLibelle() {
	  return $this->getConfig()->getLibelle();
	}
	public function getCode() {
	  return $this->getConfig()->getCode();
	}

}