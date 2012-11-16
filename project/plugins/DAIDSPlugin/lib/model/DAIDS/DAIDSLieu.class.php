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
    
    public function getMention() 
    {
        return $this->getParentNode();
    }
    
    public function getAppellation() 
    {
        return $this->getMention()->getAppellation();
    }

    public function getCertification() 
    {
        return $this->getAppellation()->getCertification();
    }

    public function getLieuxArray() 
    {
  		throw new sfException('this function need to call before lieu tree');
  	}

}