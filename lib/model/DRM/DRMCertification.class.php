<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {

	public function getPreviousSisterWithMouvementCheck() {
		
		return null;
	}

	public function getNextSisterWithMouvementCheck() {

		return null;
	}

	public function getChildrenNode() {

		return $this->genres;
	}
    
    public function hasDetailLigne($ligne)
    {
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return false;
    }

}