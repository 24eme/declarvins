<?php
/**
 * Model for DRMProduits
 *
 */

class DRMProduits extends BaseDRMProduits {
	public function getDeclaration() {
		
		return $this->getDocument()->declaration;
	}	
}