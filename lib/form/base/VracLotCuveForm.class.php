<?php
class VracLotCuveForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'numero' => new sfWidgetFormInputText(),
		   'volume' => new sfWidgetFormInputFloat(),
		   'date' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
	       'numero' => 'Année:',
	       'volume' => 'Pourcentage:',
	       'date' => 'Pourcentage:'
		));
		$this->setValidators(array(
	       'volume' => new sfValidatorNumber(array('required' => false)),
	       'numero' => new sfValidatorString(array('required' => false)),
	       'date' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('cuve[%s]');
	}
}