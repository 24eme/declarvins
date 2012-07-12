<?php
class VracStockageForm extends VracAdresseForm
{
	public function configure()
	{
		$this->widgetSchema->setNameFormat('[%s]');
	}
}