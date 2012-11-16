<?php
/**
 * Model for DAIDSCouleur
 *
 */

class DAIDSCouleur extends BaseDAIDSCouleur 
{
    
    public function getChildrenNode() 
    {
        return $this->cepages;
    }

}