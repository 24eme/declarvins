<?php
class VracLotCivpForm extends VracLotForm
{
	public function configure()
	{
		parent::configure();
		unset($this['metayage']);
		unset($this['bailleur']);
	}
}