<?php
/**
 * Model for DAIDSAppellation
 *
 */

class DAIDSAppellation extends BaseDAIDSAppellation 
{

    public function getChildrenNode() 
    {
        return $this->mentions;
    }
    
    public function getGenre() 
    {
        return $this->getParentNode();
    }
    
    public function getCertification() 
    {
        return $this->getGenre()->getCertification();
    }

}