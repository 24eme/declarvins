<?php
/**
 * Model for DRMDetail
 *
 */

class DRMDetail extends BaseDRMDetail {
    
    /**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
        return $this->getParent()->getParent();
    }
}