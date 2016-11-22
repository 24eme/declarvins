<?php

class DRMCrdSortiesForm  extends acCouchdbObjectForm {

    public function configure() {

    	$this->setWidget('utilisees', new sfWidgetFormInput());
    	$this->setValidator('utilisees', new sfValidatorInteger(array('required' => false)));

    	$this->setWidget('detruites', new sfWidgetFormInput());
    	$this->setValidator('detruites', new sfValidatorInteger(array('required' => false)));

    	$this->setWidget('manquantes', new sfWidgetFormInput());
    	$this->setValidator('manquantes', new sfValidatorInteger(array('required' => false)));
    	
        $this->widgetSchema->setNameFormat('drm_crd_sorties[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}