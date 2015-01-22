<?php

class DAIDSEntrepotForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->setWidget('commentaires', new sfWidgetFormTextarea());
    	$this->setWidget('principal', new sfWidgetFormInputCheckbox());

    	$this->setValidator('commentaires', new sfValidatorString(array('required' => false)));
    	$this->setValidator('principal', new sfValidatorBoolean(array('required' => false)));
    	
    	$this->widgetSchema->setLabel('commentaires', 'Adresse: ');
    	
        $this->widgetSchema->setNameFormat('[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}