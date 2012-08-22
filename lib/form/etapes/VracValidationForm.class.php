<?php
class VracValidationForm extends VracForm 
{
	public function configure()
    {
    	parent::configure();
		$this->useFields(array(
           'valide',
           'commentaires'
		));
        $this->widgetSchema->setNameFormat('vrac_validation[%s]');
    }

    protected function doUpdateObject($values) {

        $this->getObject()->validate();
    }
}