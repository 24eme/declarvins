<?php
/**
 * Model for DAIDSGenre
 *
 */

class DAIDSGenre extends BaseDAIDSGenre 
{
    public function getChildrenNode() 
    {
        return $this->appellations;
    }
    
    public function getCertification() 
    {
        return $this->getParentNode();
    }

}