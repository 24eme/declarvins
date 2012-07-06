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

  public function getChildrenNode() {

    return $this->details;
  }

    public function getDetailsArray() {
      $details = array();
      foreach($this->details as $detail) {
        $details[$detail->getHash()] = $detail;
      }
      return $details;
    }
}