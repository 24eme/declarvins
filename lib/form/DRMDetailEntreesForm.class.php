<?php

class DRMDetailEntreesForm  extends acCouchdbObjectForm {

    public function configure() {
    	foreach (Configuration::getStocksEntree() as $key => $item) {
	    	$this->setWidget($key, new sfWidgetFormInputFloat());
	    	$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}        
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}