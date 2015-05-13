<?php
class VracStockageForm extends VracAdresseForm
{
	public function configure()
	{
		parent::configure();
		$this->widgetSchema->setNameFormat('[%s]');
	}
}