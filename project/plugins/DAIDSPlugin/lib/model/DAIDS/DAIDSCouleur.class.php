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
    
    public function getLieu() 
    {
        return $this->getParent()->getParent();
    }

    public function getLieuxArray() 
    {
  		throw new sfException('this function need to call before lieu tree');
  	}
    

}