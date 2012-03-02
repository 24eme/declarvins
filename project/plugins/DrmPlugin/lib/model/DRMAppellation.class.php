<?php
/**
 * Model for DRMCertification
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMCertification
     */
    public function getCertification() {
        return $this->getParent()->getParent();
    }
    
	public function getProduits() {
		return $this->getDocument()->produits->get($this->getCertification()->getKey())->get($this->getKey());
	}
	
	public function calculDroits() {
		$this->total_cvo = $this->total * $this->droits->cvo->ratio;
		$this->total_douane = $this->total * $this->droits->douane->ratio;
	}
    

}