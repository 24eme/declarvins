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
	       'annee' => new sfValidatorString(array('required' => false)),
	       'pourcentage' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('millesime[%s]');
	}
}