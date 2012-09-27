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
	       'date' => new sfValidatorDate(array('date_output' => 'd/m/Y', 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4})~', 'required' => false), array('invalid' => 'Format valide : dd/mm/aaaa')),
	       'volume' => new sfValidatorNumber(array('required' => false)),
	       'montant' => new sfValidatorNumber(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('paiement[%s]');
	}
}