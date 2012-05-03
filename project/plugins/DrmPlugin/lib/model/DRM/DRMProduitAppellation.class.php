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
	
	/*
	 * Fonction basée sur le flag 
	 */
	public function hasMouvement() {
		foreach($this as $produit) {
			if (!$produit->pas_de_mouvement) {

				return true;
			}
		}

		return false;
	}
}