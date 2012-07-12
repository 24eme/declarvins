<?php
class VracMandataireForm extends VracEtablissementForm 
{
	public function configure()
	{
		$this->useFields(array(
           'raison_sociale',
           'nom',
	       'siret',
	       'carte_pro',
	       'adresse',
	       'code_postal',
	       'commune',
	       'telephone',
	       'fax',
	       'email'
		));
		$this->widgetSchema->setNameFormat('[%s]');
	}
}