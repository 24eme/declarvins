<?php
/**
 * Model for DAIDSCepage
 *
 */

class DAIDSCepage extends BaseDAIDSCepage 
{
  	public function getProduits() 
  	{
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits[$item->getHash()] = $item;
        }
        return $produits;
    }

  	public function getChildrenNode() 
  	{
    	return $this->details;
  	}
  	
  	public function getCouleur() 
  	{
    	return $this->getParentNode();
  	}

  	public function getLieuxArray() 
  	{
  		throw new sfException('this function need to call before lieu tree');
  	}

}