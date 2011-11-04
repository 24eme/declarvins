<?php
/**
 * Model for DRM
 *
 */

class DRM extends BaseDRM { 
    public function constructId() {
        $this->set('_id', 'DRM-'.$this->identifiant.'-'.$this->campagne);
    }
}