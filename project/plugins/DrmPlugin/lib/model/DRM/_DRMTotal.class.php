<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

abstract class _DRMTotal extends acCouchdbDocumentTree {
    
    public function getConfig() {
        return ConfigurationClient::getCurrent()->get($this->getHash());
    }

    public function getLibelle() {
      return $this->getConfig()->getLibelle();
    }

    public function getCode() {
      return $this->getConfig()->getCode();
    }

    protected function init() {
        parent::init();
        $this->total_debut_mois = null;
        $this->total_entrees = null;
        $this->total_sorties = null;
        $this->total = null;
    }
    
	protected function update($params = array()) {
        parent::update($params);
        $this->total_debut_mois = $this->getTotalByKey('total_debut_mois');
        $this->total_entrees = $this->getTotalByKey('total_entrees');
        $this->total_sorties = $this->getTotalByKey('total_sorties');
        $this->total = $this->get('total_debut_mois') + $this->get('total_entrees') - $this->get('total_sorties');
    }
    
    private function getTotalByKey($key) {
    	$sum = 0;
    	foreach ($this->getFields() as $field => $k) {
    		if ($this->fieldIsCollection($field)) {
    			foreach ($this->get($field) as $f => $v) {
    				if ($this->get($field)->fieldIsCollection($f)) {
    					if ($v->exist($key)) {
		    				$sum += $v->get($key);
    					}
    				}
    			}
    		}
    	}
    	return $sum;
    }

    public function hasPasDeMouvement() {

        return $this->total_entrees == 0 && $this->total_sorties == 0;
    }

    public function hasStockEpuise() {

        return$this->total_debut_mois == 0 && $this->hasPasDeMouvement();
    }
    
}