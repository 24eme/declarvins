<?php

class DSForm extends FichierForm
{
	public function configure() {
		parent::configure();
		unset($this['libelle'], $this['date_depot'], $this['visibilite']);
		$fileRequired = ($this->fichier->isNew())? true : false;
		$this->setValidator('file', new sfValidatorFile(array('required' => $fileRequired, 'path' => sfConfig::get('sf_cache_dir'))));
	}
}