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
	       'cvi',
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