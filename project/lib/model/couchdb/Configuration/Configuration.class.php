<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration {
    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }
}