<?php
/**
 * Model for DRMProduit
 *
 */

class DRMProduit extends BaseDRMProduit {
	
	const LABEL_DEFAULT_KEY = 'DEFAUT';
    
    public function getLabelObject() {
        return $this->getParent()->getParent();
    }
    public function getCertification() {
        return $this->getLabelObject()->getKey();
    }
    public function getAppellation() {
        return $this->getParent()->getKey();
    }
    public function getAppellationObject() {
        return $this->getParent();
    }
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode('-', $this->label->toArray());
    	}
    	return ($key)? $key : self::LABEL_DEFAULT_KEY;
    }
    
    public function getDetail() {
    	if ($this->label && is_array($this->label)) {
    		$label = implode('-', $this->label);
    	}
        return $this->getDocument()->declaration
                                   ->labels->get($this->getLabelObject()->getKey())
                                   ->appellations->add($this->getAppellation())
                                   ->couleurs->add($this->couleur)
                                   ->details->add(KeyInflector::slugify($this->getLabelKey()));        
    }
    
    public function existDetail() {
        return $this->getDocument()->declaration
                                   ->labels->add($this->getLabelObject()->getKey())
                                   ->appellations->add($this->getAppellation())
                                   ->couleurs->add($this->couleur)
                                   ->details->exist(KeyInflector::slugify($this->getLabelKey()));        
    }
    
    public function updateDetail() {
        $detail = $this->getDetail();
        $detail->label = $this->label;
        $detail->label_supplementaire = $this->label_supplementaire;
        
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