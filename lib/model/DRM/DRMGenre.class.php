<?php
/**
 * Model for DRMGenre
 *
 */

class DRMGenre extends BaseDRMGenre {

    /**
     *
     * @return DRMGenre
     */
    public function getCertification() {

        return $this->getParentNode();
    }
    

    public function getChildrenNode() {

        return $this->appellations;
    }
    
    public function hasDetailLigne($ligne)
    {
    	if ($configurationDetail = $this->getConfig()->exist('detail')) {
    		$line = $configurationDetail->get($ligne);
    		if (!is_null($line->readable)) {
    			return $line->readable;
    		}
    	}
    	return $this->getCertification()->hasDetailLigne($ligne);
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