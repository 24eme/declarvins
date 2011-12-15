<?php
/**
 * Model for DRMProduit
 *
 */

class DRMProduit extends BaseDRMProduit {
    
    public function getLabelObject() {
        return $this->getParent()->getParent();
    }
    public function getCertification() {
        return $this->getLabelObject()->getKey();
    }
    public function getAppellation() {
        return $this->getParent()->getKey();
    }
    
    public function getDetail() {
        return $this->getDocument()->declaration
                                   ->labels->get($this->getLabelObject()->getKey())
                                   ->appellations->add($this->getAppellation())
                                   ->couleurs->add($this->couleur)
                                   ->details->add(KeyInflector::slugify($this->denomination));        
    }
    
    public function existDetail() {
        return $this->getDocument()->declaration
                                   ->labels->add($this->getLabelObject()->getKey())
                                   ->appellations->add($this->getAppellation())
                                   ->couleurs->add($this->couleur)
                                   ->details->exist(KeyInflector::slugify($this->denomination));        
    }
    
    public function updateDetail() {
        $detail = $this->getDetail();
        $detail->denomination = $this->denomination;
        $detail->label = $this->label;
        
        return $detail;
    }
    
    public function updateProduit() {
        if ($this->existDetail()) {
            return $this->getDetail()->updateProduit($this);
        } else {
            $this->getParent()->remove($this->getKey());
            return null;
        }
    }
    
}