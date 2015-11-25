<?php
class VracAcheteurForm extends VracEtablissementForm 
{
	public function configure()
	{
		parent::configure();
		$this->useFields(array(
           'raison_sociale',
           'nom',
	       'siret',
			'num_accise',
	       'cvi',
	       'adresse',
	       'code_postal',
	       'commune',
	       'pays',
	       'telephone',
	       'fax',
	       'email'
		));
		$this->widgetSchema->setNameFormat('[%s]');
	}
}