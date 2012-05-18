<?php
/**
 * Model for DRMLieu
 *
 */

class DRMLieu extends BaseDRMLieu {

	/**
     *
     * @return DRMLieu
     */
    public function getAppellation() {

        return $this->getParentNode();
    }
	
    public function getChildrenNode() {

        return $this->couleurs;
    }

}