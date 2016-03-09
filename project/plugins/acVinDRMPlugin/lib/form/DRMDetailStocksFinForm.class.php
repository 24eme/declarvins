<?php

class DRMDetailStocksFinForm  extends acCouchdbObjectForm {

    public function configure() {

    	$contraintes = Configuration::getContraintes($this->getObject()->getParent()->getGenre()->code);
    	foreach (Configuration::getStocksFin($this->getOption('acquittes', false)) as $key => $item) {
    		if ($contraintes && !in_array('stocks_fin/'.$key, $contraintes)) {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    		} else {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    		}
    		$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}
        $this->widgetSchema->setNameFormat('drm_detail_stocks_fin[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}