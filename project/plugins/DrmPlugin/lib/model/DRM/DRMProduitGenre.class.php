<?php
/**
 * Model for DRMProduitGenre
 *
 */

class DRMProduitGenre extends BaseDRMProduitGenre {

	public function getCertification() {

		return $this->getParent();
	}

	public function getDeclaration() {

		return $this->getCertification()->getDeclaration()->genres->get($this->getKey());
    }

}