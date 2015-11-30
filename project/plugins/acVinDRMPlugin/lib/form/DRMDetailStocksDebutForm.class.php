<?php

class DRMDetailStocksDebutForm  extends acCouchdbObjectForm {

    public function configure() {
    	foreach (Configuration::getStocksDebut($this->getOption('acquittes', false)) as $key => $item) {
	    	$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
	    	$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}
        $this->widgetSchema->setNameFormat('drm_detail_stocks_debut[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}