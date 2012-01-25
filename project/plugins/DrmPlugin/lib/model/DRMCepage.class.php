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
    /**
     *
     * @return string
     * @todo A developper quand on aura des cepages
     */
    public function __toString() {
    	return '';
    }

}