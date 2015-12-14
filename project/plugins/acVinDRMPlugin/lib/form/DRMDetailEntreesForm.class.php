<?php

class DRMDetailEntreesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$contraintes = Configuration::getContraintes($this->getObject()->getParent()->getGenre()->code);
    	foreach (Configuration::getStocksEntree() as $key => $item) {
    		if ($contraintes && !in_array('entrees/'.$key, $contraintes)) {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    		} else {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    		}
	    	$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}        
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}