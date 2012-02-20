<?php
/**
 * Model for Current
 *
 */

class Current extends BaseCurrent {
    
    public function __construct() {
        parent::__construct();
        $this->set('_id', 'CURRENT');
    }
    
    public function getCampagne()
    {
    	return date('Y-m');
    }
    
}