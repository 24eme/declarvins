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

    public function getReserveInterproDureeMoisDefault() {
        if (!isset($this->configuration['reserve_interpro']['default']['duree_mois'])) {
            throw new Exception('non default duree_mois for reserve_interpro in drm.yml configuration file');
        }
        return $this->configuration['reserve_interpro']['default']['duree_mois'];
    }

    public function getReserveInterproDureeMois($hash) {
        foreach ($this->configuration['reserve_interpro'] as $ri_hash => $params) {
            if (strpos($hash, $ri_hash)) {
                if (!isset($params['duree_mois'])) {
                    return $this->getReserveInterproDureeMoisDefault();
                }
                return $params['duree_mois'];
            }
        }
        return $this->getReserveInterproDureeMoisDefault();
    }

}
