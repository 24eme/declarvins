<?php

class DAIDSDetailDouaneForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->setWidget('taux', new sfWidgetFormInputHidden());
    	$this->setValidator('taux', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('douane[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}