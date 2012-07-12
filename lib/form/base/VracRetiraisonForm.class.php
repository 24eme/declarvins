<?php
class VracRetiraisonForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
	       'lot_cuve' => new sfWidgetFormInputText(),
	       'date_retiraison' => new sfWidgetFormInputText(),
	       'volume_retire' => new sfWidgetFormInputFloat(),
		   'montant_paiement' => new sfWidgetFormInputFloat()
		));
		$this->widgetSchema->setLabels(array(
	       'lot_cuve' => 'Lot / Cuve:',
	       'date_retiraison' => 'Date de retiraison:',
	       'volume_retire' => 'Volume retiré:',
	       'montant_paiement' => 'Montant du paiement:'
		));
		$this->setValidators(array(
	       'lot_cuve' => new sfValidatorString(array('required' => false)),
	       'date_retiraison' => new sfValidatorString(array('required' => false)),
	       'volume_retire' => new sfValidatorNumber(array('required' => false)),
	       'montant_paiement' => new sfValidatorNumber(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('retiraison[%s]');
	}
}