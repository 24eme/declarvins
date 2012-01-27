<?php
/**
 * Model for DRMProduit
 *
 */

class DRMProduit extends BaseDRMProduit {
	
	const DEFAULT_KEY = 'DEFAUT';

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
    	return ($key)? $key : self::DEFAULT_KEY;
    }
    
    public function getDetail() {
        return $this->getDocument()->declaration
                                   ->certifications->add($this->getCertification()->getKey())
                                   ->appellations->add($this->getAppellation()->getKey())
                                   ->couleurs->add($this->couleur)
                                   ->cepages->add($this->cepage)
                                   ->millesimes->add($this->millesime)
                                   ->details->add(KeyInflector::slugify($this->getLabelKey()));        
    }

    public function getOrAddDetail() {
        return $this->getDetail();
    }
    
    public function existDetail() {
    	return $this->getDocument()->exist('declaration/certifications/'.$this->getCertification()->getKey().'/appellations/'.$this->getAppellation()->getKey().'/couleurs/'.$this->couleur.'/cepages/'.$this->cepage.'/millesimes/'.$this->millesime.'/details/'.KeyInflector::slugify($this->getLabelKey()));        
    }
    
    public function updateDetail() {
        $detail = $this->getOrAddDetail();
        $detail->label = $this->label;
        $detail->label_supplementaire = $this->label_supplementaire;        
        return $detail;
    }

    public function delete() {
        $this->getDetail()->delete();
    }
    
    /*public function updateProduit() {
        if ($this->existDetail()) {
            return $this->getDetail()->updateProduit($this);
        } else { 
            $this->getParent()->remove($this->getKey());
            return null;
        }
    }*/
    
}