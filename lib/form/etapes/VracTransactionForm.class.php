<?php
class VracTransactionForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'commentaires',
           'lots',
		   'volume_propose'
		));
		$this->setWidget('volume_propose', new sfWidgetFormInputHidden());
		$this->setValidator('volume_propose', new sfValidatorPass());
  		$this->validatorSchema->setPostValidator(new VracVolumesValidator());
		$this->widgetSchema->setNameFormat('vrac_transaction[%s]');
    }
}