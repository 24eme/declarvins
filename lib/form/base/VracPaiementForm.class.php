<?php
class VracPaiementForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'date' => new sfWidgetFormInputText(),
		   'montant' => new sfWidgetFormInputFloat()
		));
		$this->widgetSchema->setLabels(array(
	       'date' => 'Date du paiement:',
	       'montant' => 'Montant du paiement:'
		));
		$this->setValidators(array(
	       'date' => new sfValidatorString(array('required' => false)),
	       'montant' => new sfValidatorNumber(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('paiement[%s]');
	}
}