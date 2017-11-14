<?php
class VracLotMillesimeForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'annee' => new sfWidgetFormInputText(),
		   'pourcentage' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'annee' => 'Année:',
	       'pourcentage' => 'Pourcentage:'
		));
		$this->setValidators(array(
	       'annee' => new sfValidatorRegex(array('required' => true, 'pattern' => '/^[0-9]{4}$/')),
	       'pourcentage' => new sfValidatorString(array('required' => true))
		));
		$this->widgetSchema->setNameFormat('millesime[%s]');
	}
}