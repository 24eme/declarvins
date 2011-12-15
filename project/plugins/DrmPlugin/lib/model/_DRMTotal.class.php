<?php
/**
 * Inheritance tree class _DRMTotal
 *
 */

abstract class _DRMTotal extends acCouchdbDocumentTree {
    
    public function getConfig() {
        
        return ConfigurationClient::getCurrent()->get($this->getHash());
    }
    
}