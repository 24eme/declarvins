<?php

class DAIDSDetailCvoForm extends acCouchdbObjectForm 
{
    public function configure() 
    {
    	$this->setWidget('total_cvo', new sfWidgetFormInputFloat());
    	$this->setValidator('total_cvo', new sfValidatorNumber(array('required' => true)));
        
        $this->widgetSchema->setNameFormat('daids_detail_cvo[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    public function doUpdateObject($values) 
    {
    	parent::doUpdateObject($values);
        $this->getObject()->getDocument()->update(array('cvo_manuel' => true));
    }
}