<?php

class DSForm extends FichierForm
{
	public function configure() {
		parent::configure();
		unset($this['libelle'], $this['date_depot'], $this['visibilite']);
	}
}