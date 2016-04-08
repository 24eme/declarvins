<?php
/**
 * Model for DRMCepage
 *
 */

class DRMCepage extends BaseDRMCepage {
	
	/**
     *
     * @return DRMCouleur
     */
  	public function getCouleur() {
   
    	return $this->getParentNode();
  	}

  	public function getProduits() {
        $produits = array();
        foreach($this->getChildrenNode() as $key => $item) {
            $produits[$item->getHash()] = $item;
        }
        return $produits;
    }

  	public function getProduitsCepages() {
        return array($this->getHash() => $this);
    }

  	public function getLieuxArray() {

  		throw new sfException('this function need to call before lieu tree');
  	}

    public function getDetailsArray() {
      	$details = array();
      	foreach($this->details as $detail) {
        	$details[$detail->getHash()] = $detail;
      	}
      	
      	return $details;
    }

  	public function getChildrenNode() {

    	return $this->details;
  	}

}