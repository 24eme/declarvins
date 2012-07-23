<?php
class VracValidationIrForm extends VracForm 
{
	public function configure()
    {
    	parent::configure();
		$this->useFields(array(
           'valide'
		));
        $this->widgetSchema->setNameFormat('vrac_validation[%s]');
    }
}