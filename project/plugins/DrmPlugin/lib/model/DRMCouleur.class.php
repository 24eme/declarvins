<?php
/**
 * Model for DRMCouleur
 *
 */

class DRMCouleur extends BaseDRMCouleur {
    
    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {
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