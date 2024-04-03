<?php

class DRMValidationObservationsCrdCollectionForm extends BaseForm
{
        protected $_drm;

    	public function __construct(DRM $drm, $options = array(), $CSRFSecret = null) {
        	$this->_drm = $drm;
        	parent::__construct(array(), $options, $CSRFSecret);
    	}

      public function configure() {
      		foreach ($this->_drm->crds as $crd) {
              if ($crd->needObservation()) {
                  $this->embedForm ($crd->getHash(), new DRMValidationObservationCrdForm($crd));
              }
      		}
  	}
}
