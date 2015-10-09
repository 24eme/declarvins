<?php
class VracAdresseForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'siret' => new sfWidgetFormInputText(),
	       'libelle' => new sfWidgetFormInputText(),
		   'adresse' => new sfWidgetFormInputText(),
	       'code_postal' => new sfWidgetFormInputText(),
	       'commune' => new sfWidgetFormInputText(),
	       'pays' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
		   'siret' => 'SIRET:',
		   'libelle' => 'Nom commercial:',
	       'adresse' => 'Adresse:',
	       'code_postal' => 'Code postal:',
	       'commune' => 'Commune:',
	       'pays' => 'Pays:'
		));
		$this->setValidators(array(
			'siret' => new ValidatorSiret(array('required' => false)),
		   'libelle' => new sfValidatorString(array('required' => false)),
	       'adresse' => new sfValidatorString(array('required' => false)),
	       'code_postal' => new sfValidatorString(array('required' => false)),
	       'commune' => new sfValidatorString(array('required' => false)),
	       'pays' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('vrac_adresse[%s]');
	}
}