<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

class _DRMTotal extends acCouchdbDocumentTree {
    
    public function getConfig() {
        
        return ConfigurationClient::getCurrent()->get($this->getHash());
    }
    
}