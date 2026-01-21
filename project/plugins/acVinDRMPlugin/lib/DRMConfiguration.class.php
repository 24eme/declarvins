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

    public function getReserveInterproParamDefault($param_key) {
        if (!isset($this->configuration['reserve_interpro']['default'][$param_key])) {
            throw new Exception('non default '.$param_key.' for reserve_interpro in drm.yml configuration file');
        }
        return $this->configuration['reserve_interpro']['default'][$param_key];
    }

    public function getReserveInterproParamValue($hash, $param_key) {
        foreach ($this->configuration['reserve_interpro'] as $ri_hash => $params) {
            if (strpos($hash, $ri_hash)) {
                if (!isset($params[$param_key])) {
                    return $this->getReserveInterproParamDefault($param_key);
                }
                return $params[$param_key];
            }
        }
        return $this->getReserveInterproParamDefault($param_key);
    }



}
