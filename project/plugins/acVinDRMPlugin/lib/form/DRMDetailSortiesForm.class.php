<?php

class DRMDetailSortiesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$stockSorties = Configuration::getStocksSortie(); 
    	unset($stockSorties['vrac_contrat']); 
    	$contraintes = Configuration::getContraintes($this->getObject()->getParent()->getGenre()->code);
    	foreach ($stockSorties as $key => $item) {
    		if ($contraintes && !in_array('sorties/'.$key, $contraintes)) {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f"), array('readonly' => 'readonly')));
    		} else {
    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.04f")));
    		}
    	}
        $this->widgetSchema->setNameFormat('drm_detail_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}