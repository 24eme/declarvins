<?php

class DRMDeclaratifReportForm extends acCouchdbForm {

    private $_drm = null;
    
    public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        $this->_drm = $drm;
        parent::__construct($drm, $this->getDefaultValues(), $options, $CSRFSecret);
    }

    public function getDefaultValues() {
		$reports = $this->_drm->declaratif->getOrAdd('reports');
        return $reports->toArray();;
    }

    protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        $defaults = $this->getDefaults();
        foreach ($this->_drm->declaratif->reports as $key => $value) {
        	$defaults[$key] = $value;
        }
        $this->setDefaults($defaults);     
    }

    public function configure() {
    	$labels = array();
    	foreach (DRMDroitsCirculation::getCodes() as $code) {
    		$key = $code.'_'.DRMDroitsCirculation::KEY_VIRTUAL_TOTAL;
    		$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.02f")));
    		$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    		$labels[$key] = $code.' :';
    	}
    	$this->widgetSchema->setLabels($labels);
        $this->widgetSchema->setNameFormat('%s');
    }
    
    public function getObject() {
    	return $this->_drm;
    }

}