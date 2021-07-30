<?php

class DSNegoceUploadForm extends FichierForm
{
	public function configure() {
		parent::configure();
		unset($this['libelle'], $this['date_depot'], $this['visibilite']);
		$fileRequired = ($this->fichier->isNew())? true : false;
		$this->setValidator('file', new sfValidatorFile(array('required' => $fileRequired, 'path' => sfConfig::get('sf_cache_dir'))));

		$this->setWidget('interpro', new bsWidgetFormChoice(array('expanded' => true, 'choices' => $this->getInterpro())));
		$this->setValidator('interpro', new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getInterpro()))));

		$interpro = $this->fichier->getEtablissementObject()->getInterproObject();

		$this->getWidget('interpro')->setLabel('Modèle DS ');
		$this->getWidget('interpro')->setDefault($interpro->_id);
	}

	public function getInterpro() {
		return array('INTERPRO-IR' => 'Inter-Rhône', 'INTERPRO-CIVP' => 'Provence');
	}
}
