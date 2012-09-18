<?php
class VracPaiementForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'date' => new sfWidgetFormInputText(),
		   'volume' => new sfWidgetFormInputFloat(),
		   'montant' => new sfWidgetFormInputFloat()
		));
		$this->widgetSchema->setLabels(array(
	       'date' => 'Date du paiement :',
	       'volume' => 'Volume :',
	       'montant' => 'Montant :'
		));
		$this->setValidators(array(
	       'date' => new sfValidatorString(array('required' => false)),
	       'volume' => new sfValidatorNumber(array('required' => false)),
	       'montant' => new sfValidatorNumber(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('paiement[%s]');
	}
}