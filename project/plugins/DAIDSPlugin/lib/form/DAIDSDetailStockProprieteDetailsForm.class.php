<?php

class DAIDSDetailStockProprieteDetailsForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->setWidget('reserve', new sfWidgetFormInputFloat());
    	$this->setWidget('vrac_vendu', new sfWidgetFormInputFloat());
    	$this->setWidget('vrac_libre', new sfWidgetFormInputFloat());
    	$this->setWidget('conditionne', new sfWidgetFormInputFloat());
    	$this->setValidator('reserve', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('vrac_vendu', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('vrac_libre', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('conditionne', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('stock_propriete_details[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}