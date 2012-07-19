<?php
class VracLivraisonForm extends VracAdresseForm
{
	public function configure()
	{
		parent::configure();
		$this->widgetSchema->setNameFormat('[%s]');
	}
}