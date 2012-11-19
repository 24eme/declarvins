<?php

class DAIDSDetailStocksForm  extends acCouchdbObjectForm 
{

    public function configure() 
    {
    	$this->setWidget('chais', new sfWidgetFormInputFloat());
    	$this->setWidget('propriete_tiers', new sfWidgetFormInputFloat());
    	$this->setWidget('tiers', new sfWidgetFormInputFloat());
    	$this->setValidator('chais', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('propriete_tiers', new sfValidatorNumber(array('required' => false)));
    	$this->setValidator('tiers', new sfValidatorNumber(array('required' => false)));
    		    		
        $this->widgetSchema->setNameFormat('stocks[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }

}