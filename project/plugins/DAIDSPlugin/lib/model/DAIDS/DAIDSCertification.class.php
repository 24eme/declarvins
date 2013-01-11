<?php
/**
 * Model for DAIDSCertification
 *
 */

class DAIDSCertification extends BaseDAIDSCertification 
{

	public function getChildrenNode() 
	{
		return $this->genres;
	}
	public function getPreviousSisterWithParent() 
	{
        $item = $this->getPreviousSister();
        $sister = null;
        if ($item) {
            $sister = $item;
        }
        return $sister;
	}

	public function getNextSisterWithParent() 
	{
		$item = $this->getNextSister();
        $sister = null;
        if ($item) {
            $sister = $item;
        }
        return $sister;
	}

}