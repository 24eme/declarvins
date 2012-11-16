<?php
/**
 * Model for DAIDSMention
 *
 */

class DAIDSMention extends BaseDAIDSMention 
{
    public function getChildrenNode() 
    {
        return $this->lieux;
    }

}