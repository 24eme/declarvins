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
		$this->setValidator('volume_propose', new ValidatorPass());
		$this->setWidget('date_limite_retiraison', new sfWidgetFormInputHidden());
		$this->setValidator('date_limite_retiraison', new sfValidatorDate(array('date_output' => 'Y-m-d', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false)));
  		$this->validatorSchema->setPostValidator(new VracTransactionValidator());
		$this->widgetSchema->setNameFormat('vrac_transaction[%s]');
    }
}