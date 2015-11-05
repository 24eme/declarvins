<?php
class VracMandataireForm extends VracEtablissementForm 
{
	public function configure()
	{
		parent::configure();
		$this->useFields(array(
           'raison_sociale',
           'nom',
	       'siret',
			'num_accise',
	       'no_carte_professionnelle',
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