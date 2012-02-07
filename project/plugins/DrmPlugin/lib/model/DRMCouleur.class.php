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
    
}