<?php
/**
 * Model for DAIDSLieu
 *
 */

class DAIDSLieu extends BaseDAIDSLieu 
{
	
    public function getChildrenNode() 
    {
        return $this->couleurs;
    }

}