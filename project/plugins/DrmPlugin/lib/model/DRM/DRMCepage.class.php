<?php
/**
 * Model for DRMCepage
 *
 */

class DRMCepage extends BaseDRMCepage {
	/**
     *
     * @return DRMCouleur
     */
    public function getCouleur() {
    	
        return $this->getParent()->getParent();
    }

}