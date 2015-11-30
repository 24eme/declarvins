<?php

class DRMDetailSortiesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$stockSorties = Configuration::getStocksSortie($this->getOption('acquittes', false)); 
    	if (isset($stockSorties['vrac_contrat'])) {
    		unset($stockSorties['vrac_contrat']);
    	}
    	foreach ($stockSorties as $key => $item) {
	    	$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
	    	$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}        
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}