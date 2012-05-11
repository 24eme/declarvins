<?php
/**
 * Model for DRMProduitCertification
 *
 */

class DRMProduitCertification extends BaseDRMProduitCertification {

	public function getDeclaration() {

		return $this->getParent()->getDeclaration()->certifications->get($this->getKey());
	}

	public function getConfig() {

		return $this->getDeclaration()->getConfig();
	}

		
}