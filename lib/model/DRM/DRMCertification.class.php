<?php
/**
 * Model for DRMCertification
 *
 */

class DRMCertification extends BaseDRMCertification {

	public function getPreviousSisterWithMouvementCheck() {
        $item = $this->getPreviousSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getPreviousSisterWithMouvementCheck();
        }

        return $sister;
	}

	public function getNextSisterWithMouvementCheck() {
		$item = $this->getNextSister();
        $sister = null;

        if ($item) {
            $sister = $item;
        }

        if ($sister && !$sister->hasMouvementCheck()) {

            return $sister->getNextSisterWithMouvementCheck();
        }

        return $sister;
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
    
    /*public function getDroit($type) 
    {
    	if ($this->getConfig()->hasDroits()) {
    		if (count($this->getConfig()->getDroits($this->getInterproKey())->get($type)) > 0) {
    			return $this->getConfig()->getDroits($this->getInterproKey())->get($type)->getCurrentDroit($this->getPeriode());
    		}
    	}
      	return null;
    }*/

}