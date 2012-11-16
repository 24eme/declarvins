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

}