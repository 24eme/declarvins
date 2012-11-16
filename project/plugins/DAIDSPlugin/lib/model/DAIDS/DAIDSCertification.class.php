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

}