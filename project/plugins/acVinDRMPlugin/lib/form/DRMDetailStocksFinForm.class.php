<?php

class DRMDetailStocksFinForm  extends acCouchdbObjectForm {

    public function configure() {
    	foreach (Configuration::getStocksFin($this->getOption('acquittes', false)) as $key => $item) {
    		if ($key == 'bloque') {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.05f"), array('readonly' => 'readonly')));
    		} else {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.05f")));
    		}
    		$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}
        $this->widgetSchema->setNameFormat('drm_detail_stocks_fin[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

	   protected function updateDefaultsFromObject() {
        parent::updateDefaultsFromObject();
        if ($r = $this->getObject()->getParent()->getReserveInterpro()) {
        	$defaults = $this->getDefaults();
        	$defaults['bloque'] = $r;
        	$this->setDefaults($defaults);
        }
      }

}
