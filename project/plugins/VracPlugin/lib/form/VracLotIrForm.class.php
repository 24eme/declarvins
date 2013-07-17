<?php
class VracLotIrForm extends VracLotForm
{
	public function configure()
	{
		parent::configure();
		unset($this['metayage']);
		unset($this['bailleur']);
		unset($this['presence_allergenes']);
		unset($this['allergenes']);
		unset($this['degre']);
	}
}