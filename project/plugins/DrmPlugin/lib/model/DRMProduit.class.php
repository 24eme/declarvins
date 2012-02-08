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
    
    public function getLabelKey() {
    	$key = null;
    	if ($this->label) {
    		$key = implode('-', $this->label->toArray());
    	}
    	return ($key)? $key : DRM::DEFAULT_KEY;
    }
    
    public function getDetail() {
        return $this->store('detail', array($this, 'getOrAddDetail')); 
    }

    protected function getOrAddDetail() {

        return $this->getDocument()->declaration->getOrAdd($this->getHashDetail());
    }

    public function getHashDetail() {
        return 'certifications/'.$this->getCertification()->getKey().
               '/appellations/'.$this->getAppellation()->getKey().
               '/lieux/'.$this->lieu.
               '/couleurs/'.$this->couleur.
               '/cepages/'.$this->cepage.
               '/millesimes/'.$this->millesime.
               '/details/'.KeyInflector::slugify($this->getLabelKey());
    }
    
    public function existDetail() {

    	return $this->getDocument()->declaration->exist($this->getHashDetail());        
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