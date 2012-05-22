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

    public function getCertification() {
        
        return $this->getAppellation()->getCertification();
    }
	
    public function getChildrenNode() {

        return $this->couleurs;
    }

    public function getLieux() {

        return null;
    }

}