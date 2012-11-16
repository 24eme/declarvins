<?php
/**
 * Model for DRMLieu
 *
 */

class DRMLieu extends BaseDRMLieu {

	/**
     *
     * @return DRMLieu
     */
    public function getMention() {

        return $this->getParentNode();
    }
    public function getAppellation() {

        return $this->getMention()->getAppellation();
    }

    public function getCertification() {
        
        return $this->getAppellation()->getCertification();
    }
	
    public function getChildrenNode() {

        return $this->couleurs;
    }

    public function getLieuxArray() {

  		throw new sfException('this function need to call before lieu tree');
  	}
    
    public function hasDetailLigne($ligne)
    {
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return $this->getAppellation()->hasDetailLigne($ligne);
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