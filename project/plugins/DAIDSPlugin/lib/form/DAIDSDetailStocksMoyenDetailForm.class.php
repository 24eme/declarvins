<?php
class DAIDSDetailStocksMoyenDetailForm extends acCouchdbObjectForm
{
	public function configure() 
    {
    	$this->setWidget('taux', new sfWidgetFormInputFloat());
    	$this->setWidget('volume', new sfWidgetFormInputFloat());
    	$this->setValidator('taux', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('volume', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
}