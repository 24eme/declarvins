<?php
class VracEtablissementForm extends acCouchdbObjectForm
{
	public function configure()
	{
		$this->setWidgets(array(
           'raison_sociale' => new sfWidgetFormInputText(),
           'nom' => new sfWidgetFormInputText(),
	       'siret' => new sfWidgetFormInputText(array(), array('maxlength' => 14)),
	       'cvi' => new sfWidgetFormInputText(),
	       'num_accise' => new sfWidgetFormInputText(),
	       'num_tva_intracomm' => new sfWidgetFormInputText(),
		   'carte_pro' => new sfWidgetFormInputText(),
	       'adresse' => new sfWidgetFormTextarea(),
	       'code_postal' => new sfWidgetFormInputText(),
	       'commune' => new sfWidgetFormInputText(),
	       'telephone' => new sfWidgetFormInputText(),
	       'fax' => new sfWidgetFormInputText(),
	       'email' => new sfWidgetFormInputText()
		));
		$this->widgetSchema->setLabels(array(
           'raison_sociale' => 'Raison sociale:',
           'nom' => 'Nom commercial:',
	       'siret' => 'SIRET:',
	       'cvi' => 'CVI:',
	       'num_accise' => 'Numéro accises:',
	       'num_tva_intracomm' => 'Numéro TVA intracommunautaire:',
	       'carte_pro' => 'Numéro de carte professionnelle:',
	       'adresse' => 'Adresse:',
	       'code_postal' => 'Code postal:',
	       'commune' => 'Commune:',
	       'telephone' => 'Téléphone:',
	       'fax' => 'Fax:',
	       'email' => 'Email:'
		));
		$this->setValidators(array(
       	   'raison_sociale' => new sfValidatorString(array('required' => false)),
       	   'nom' => new sfValidatorString(array('required' => false)),
	       'siret' => new ValidatorSiret(array('required' => false)),
	       'cvi' => new sfValidatorString(array('required' => false, 'max_length' => 11, 'min_length' => 9)),
	       'num_accise' => new sfValidatorString(array('required' => false)),
	       'num_tva_intracomm' => new sfValidatorString(array('required' => false)),
	       'carte_pro' => new sfValidatorString(array('required' => false)),
	       'adresse' => new sfValidatorString(array('required' => false)),
	       'code_postal' => new sfValidatorString(array('required' => false)),
	       'commune' => new sfValidatorString(array('required' => false)),
	       'telephone' => new sfValidatorString(array('required' => false)),
	       'fax' => new sfValidatorString(array('required' => false)),
	       'email' => new sfValidatorString(array('required' => false))
		));
		$this->widgetSchema->setNameFormat('vrac_etablissement[%s]');
	}
}