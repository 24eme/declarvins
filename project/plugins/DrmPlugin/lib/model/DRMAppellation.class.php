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
    /**
     *
     * @return string
     */
    public function __toString() {
    	return ConfigurationClient::getCurrent()
    							->declaration
    							->certifications
    							->get($this->getCertification()->getKey())
    							->appellations
    							->get($this->getKey())
    							->libelle;
    }
}