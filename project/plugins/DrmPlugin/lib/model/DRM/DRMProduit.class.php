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

    protected function getLabelKeyFromValues($labels) {
        $key = null;
        if ($labels && is_array($labels) && count($labels) > 0) {
           sort($labels);
           $key = implode('-', $labels);
        }
        
        return ($key)? $key : DRM::DEFAULT_KEY;
    }

    public function getLabelKey() {
        
        return $this->getLabelKeyFromValues($this->label->toArray());
    }
    
    public function getDetail() {
        return $this->store('detail', array($this, 'getOrAddDetail')); 
    }

    protected function getOrAddDetail() {

        return $this->getDocument()->getOrAdd($this->getHashDetail());
    }

    public function getHashDetailFromValues($hashref, $labels) {
        
        return $hashref.'/details/'.KeyInflector::slugify($this->getLabelKeyFromValues($labels));
    }

    public function getHashDetail() {
        
        return $this->getHashDetailFromValues($this->hashref, $this->label->toArray());
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
    	$hash = $this->getAppellation()->getHash();
        $this->getDocument()->remove($this->getDetail()->getAppellation()->getHash());
        parent::delete();
        parent::clean($hash);
    }
    
}