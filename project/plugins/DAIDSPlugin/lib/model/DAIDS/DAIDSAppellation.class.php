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

}