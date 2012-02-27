<?php
/**
 * Model for DRMProduitAppellation
 *
 */

class DRMProduitAppellation extends BaseDRMProduitAppellation {
	

	public function getCertification()
	{
		
		return $this->getParent();
	}

	public function getDeclaration() {

		return $this->getCertification()->getDeclaration()->appellations->get($this->getKey());
	}
}