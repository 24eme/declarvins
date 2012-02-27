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
    
	public function getProduitsAppellation() {
		return $this->getDocument()->produits->get($this->getCertification()->getKey())->get($this->getKey());
	}
    

}