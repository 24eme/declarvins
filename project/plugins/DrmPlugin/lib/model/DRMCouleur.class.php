<?php
/**
 * Model for DRMCouleur
 *
 */

class DRMCouleur extends BaseDRMCouleur {
    
    /**
     *
     * @return DRMAppellation
     */
    public function getAppellation() {
        return $this->getParent()->getParent();
    }
    
    /**
     *
     * @return string
     */
    public function __toString() {
        return ucfirst($this->getKey());
    }
}