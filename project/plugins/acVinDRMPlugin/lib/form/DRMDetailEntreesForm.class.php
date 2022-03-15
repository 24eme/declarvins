<?php

class DRMDetailEntreesForm  extends acCouchdbObjectForm {

    public function configure() {
    	$contraintes = Configuration::getContraintes($this->getObject()->getParent());
    	$stockEntrees = Configuration::getStocksEntree(true);
    	$stockEntrees = array_merge($stockEntrees, Configuration::getStocksEntree(false));
    	foreach ($stockEntrees as $key => $item) {
    		if ($this->getObject()->exist($key.'_details') && (($this->getUser()->getCompte()->isTiers() && $this->getObject()->getDocument()->getEtablissementObject()->isTransmissionCiel()) || $this->getObject()->getDocument()->isNegoce())) {
    			$this->setWidget($key, new sfWidgetFormInputHidden());
    		} else {
	    		if ($contraintes && !in_array('entrees/'.$key, $contraintes)) {
	    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.05f"), array('readonly' => 'readonly')));
	    		} else {
	    			$this->setWidget($key, new sfWidgetFormInputFloat(array('float_format' => "%01.05f")));
	    		}
    		}
    		if ($this->getObject()->getDocument()->isNegoce() && $key == 'vci') {
    		    $this->getWidget($key)->setAttribute('readonly', 'readonly');
    		}
	    	$this->setValidator($key, new sfValidatorNumber(array('required' => false)));
    	}   
        $this->widgetSchema->setNameFormat('drm_detail_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

    protected function getUser() {

        return sfContext::getInstance()->getUser();
    }

}