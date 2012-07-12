<?php
class VracValideForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'date_saisie' => new sfWidgetFormInputText(),
	       'identifiant' => new sfWidgetFormInputText(),
	       'statut' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'date_saisie' => 'Date de saisie:',
	       'identifiant' => 'Identifiant:',
	       'statut' => 'Statut:'
		));
		$this->setValidators(array(
	       'date_saisie' => new sfValidatorString(array('required' => false)),
	       'identifiant' => new sfValidatorString(array('required' => false)),
	       'statut' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('[%s]');
	}
}