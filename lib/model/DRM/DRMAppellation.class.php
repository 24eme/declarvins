<?php
/**
 * Model for DRMCertification
 */

class DRMAppellation extends BaseDRMAppellation {
    
    /**
     *
     * @return DRMCertification
     */
    public function getGenre() {

        return $this->getParentNode();
    }

    public function getChildrenNode() {

        return $this->mentions;
    }

     /**
     *
     * @return DRMGenre
     */
    public function getCertification() {
        return $this->getGenre()->getParent()->getParent();
    }
    
    public function hasDetailLigne($ligne)
    {
    	throw new sfException('fonction obsolete');
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return $this->getGenre()->hasDetailLigne($ligne);
    }
}
