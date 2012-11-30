<?php
/**
 * Model for DRMGenre
 *
 */

class DRMGenre extends BaseDRMGenre {

    /**
     *
     * @return DRMGenre
     */
    public function getCertification() {

        return $this->getParentNode();
    }
    

    public function getChildrenNode() {

        return $this->appellations;
    }
    
    public function hasDetailLigne($ligne)
    {
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return $this->getCertification()->hasDetailLigne($ligne);
    }

}