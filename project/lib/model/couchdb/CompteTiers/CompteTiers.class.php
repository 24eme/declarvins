<?php

class CompteTiers extends BaseCompteTiers {
    
    public function getTiersCollection() {
        return sfCouchdbManager::getClient()->keys(array_keys($this->getTiers()->toArray()))->execute();
    }
    
}