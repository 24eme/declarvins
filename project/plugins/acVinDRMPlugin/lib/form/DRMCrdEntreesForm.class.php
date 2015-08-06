<?php

class DRMCrdEntreesForm  extends acCouchdbObjectForm {

    public function configure() {

    	$this->setWidget('achats', new sfWidgetFormInput());
    	$this->setValidator('achats', new sfValidatorInteger(array('required' => false)));

    	$this->setWidget('excedents', new sfWidgetFormInput());
    	$this->setValidator('excedents', new sfValidatorInteger(array('required' => false)));

    	$this->setWidget('retours', new sfWidgetFormInput());
    	$this->setValidator('retours', new sfValidatorInteger(array('required' => false)));
    	
        $this->widgetSchema->setNameFormat('drm_crd_entrees[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}