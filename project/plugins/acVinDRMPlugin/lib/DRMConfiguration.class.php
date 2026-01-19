<?php

class DRMConfiguration {

    private static $_instance = null;
    protected $configuration;

    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new DRMConfiguration();
        }
        return self::$_instance;
    }

    public function __construct() {
        if(!sfConfig::has('drm_configuration_drm')) {
           throw new sfException("La configuration pour les DRM n'a pas été définie pour cette application");
	    }
        $this->configuration = sfConfig::get('drm_configuration_drm', array());
    }

    public function getAll() {
        return $this->configuration;
    }

}
