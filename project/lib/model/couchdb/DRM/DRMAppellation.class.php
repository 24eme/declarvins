<?php
/**
 * Model for DRMAppellation
 *
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMLabel
     */
    public function getLabel() {
        return $this->getParent()->getParent();
    }
}