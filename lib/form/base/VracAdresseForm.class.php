<?php
class VracAdresseForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'adresse' => new sfWidgetFormInputText(),
	       'code_postal' => new sfWidgetFormInputText(),
	       'commune' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'adresse' => 'Adresse:',
	       'code_postal' => 'Code postal:',
	       'commune' => 'Commune:'
		));
		$this->setValidators(array(
	       'adresse' => new sfValidatorString(array('required' => false)),
	       'code_postal' => new sfValidatorString(array('required' => false)),
	       'commune' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('vrac_adresse[%s]');
	}
}