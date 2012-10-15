<?php
class VracTransactionForm extends VracForm 
{
   	public function configure()
    {
		parent::configure();
		$this->useFields(array(
           'commentaires',
           'lots',
		   'volume_propose',
		   'date_limite_retiraison'
		));
		$this->setWidget('volume_propose', new sfWidgetFormInputHidden());
		$this->setValidator('volume_propose', new sfValidatorPass());
		$this->setWidget('date_limite_retiraison', new sfWidgetFormInputHidden());
		$this->setValidator('date_limite_retiraison', new sfValidatorPass());
  		$this->validatorSchema->setPostValidator(new VracTransactionValidator());
		$this->widgetSchema->setNameFormat('vrac_transaction[%s]');
    }
}