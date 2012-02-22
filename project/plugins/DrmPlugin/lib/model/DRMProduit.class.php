<?php
/**
 * Model for DRMProduit
 *
 */

class DRMProduit extends BaseDRMProduit {
	
    public function getCertification() {
        return $this->getAppellation()->getParent();
    }
    
    public function getAppellation() {
        return $this->getParent();
    }
    
    public function setLabel($label) {
      sort($label);
      $this->_set('label', $label);
    }

    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
	  $labels = $this->label->toArray();
	  sort($labels);
	  $key = implode('-', $labels);
    	}
    	return ($key)? $key : DRM::DEFAULT_KEY;
    }
    
    public function getDetail() {
        return $this->store('detail', array($this, 'getOrAddDetail')); 
    }

    protected function getOrAddDetail() {

        return $this->getDocument()->getOrAdd($this->getHashDetail());
    }

    public function getHashDetail() {
        return $this->hashref.'/details/'.KeyInflector::slugify($this->getLabelKey());
    }
    
    public function existDetail() {

    	return $this->getDocument()->exist($this->getHashDetail());        
    }
    
    public function updateDetail() {
        $detail = $this->getOrAddDetail();
        $detail->label = $this->label;
        $detail->label_supplementaire = $this->label_supplementaire;        
        return $detail;
    }

    public function delete() {
        $this->getDetail()->delete();
        parent::delete();
    }
    
}