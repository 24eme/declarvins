<?php
/**
 * Model for Configuration
 *
 */

class Configuration extends BaseConfiguration {

	const DEFAULT_KEY = 'DEFAUT';

    public function constructId() {
        $this->set('_id', "CONFIGURATION");
    }
}