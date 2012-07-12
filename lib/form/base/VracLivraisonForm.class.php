<?php
class VracLivraisonForm extends VracAdresseForm
{
	public function configure()
	{
		$this->widgetSchema->setNameFormat('[%s]');
	}
}