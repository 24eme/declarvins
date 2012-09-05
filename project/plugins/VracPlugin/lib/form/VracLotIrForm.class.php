<?php
class VracLotIrForm extends VracLotForm
{
	public function configure()
	{
		parent::configure();
		unset($this['presence_allergenes']);
	}
}