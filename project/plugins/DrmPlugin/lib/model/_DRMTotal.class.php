<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

abstract class _DRMTotal extends acCouchdbDocumentTree {
    
    public function getConfig() {
        
        return ConfigurationClient::getCurrent()->get($this->getHash());
    }
    
	protected function update($params = array()) {
        parent::update($params);
        $this->set('total_debut_mois', $this->getTotalByKey('total_debut_mois'));
        $this->set('total_entrees', $this->getTotalByKey('total_entrees'));
        $this->set('total_sorties', $this->getTotalByKey('total_sorties'));
        $this->set('total', $this->get('total_debut_mois') + $this->get('total_entrees') -  $this->get('total_sorties'));
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
    
}