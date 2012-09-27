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
	       'date_saisie' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false), array('invalid' => 'Format valide : dd/mm/aaaa')),
	       'identifiant' => new sfValidatorString(array('required' => false)),
	       'statut' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('[%s]');
	}
}