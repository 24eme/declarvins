<?php
/**
 * Model for DRMProduitAppellation
 *
 */

class DRMProduitAppellation extends BaseDRMProduitAppellation {
	public function getGenre()
	{
		return $this->getParent();
	}
	

	public function getCertification()
	{
		return $this->getGenre()->getCertification();
	}

	public function getDeclaration() {

		return $this->getGenre()->getDeclaration()->appellations->get($this->getKey());
	}
}