<?php
/**
 * Model for DRMCouleur
 *
 */

class DRMCouleur extends BaseDRMCouleur {
    
    /**
     *
     * @return DRMLieu
     */
    public function getLieu() {
        
        return $this->getParent()->getParent();
    }
    
    public function getChildrenNode() {

        return $this->cepages;
    }

    public function getLieuxArray() {

  		throw new sfException('this function need to call before lieu tree');
  	}
    
    public function getDroit($type) {
      return $this->getConfig()->getDroits($this->getInterproKey())->get($type)->getCurrentDroit($this->getPeriode());
    }

    public function getDroits() {
      $conf = $this->getConfig();
      $droits = array();
      foreach ($conf->getDroits($this->getInterproKey()) as $key => $droit) {
	$droits[$key] = $droit->getCurrentDroit($this->getPeriode());
      }
      return $droits;
    }
    public function getInterproKey() {
      if (!$this->getDocument()->getInterpro())
	return array();
      return $this->getDocument()->getInterpro()->get('_id');
    }
    public function getPeriode() {
      return $this->getDocument()->getPeriode();
    }
	
}