<?php
class ContratForm extends acCouchdbObjectForm {

    public function configure() {
    	$this->setWidgets(array(
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'fonction' => new sfWidgetFormInputText(),
			'telephone' => new sfWidgetFormInputText(),
			'fax' => new sfWidgetFormInputText(), 	
            'email' => new sfWidgetFormInputText(),
        	'email2' => new sfWidgetFormInputText(),
        	'dematerialise_ciel' => new WidgetFormInputCheckbox()
    					
    	));
		$this->widgetSchema->setLabels(array(
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'fonction' => 'Fonction*: ',
			'telephone' => 'Téléphone*: ',
			'fax' => 'Fax: ',
            'email' => 'Adresse e-mail*: ',
            'email2' => 'Vérification de l\'e-mail*: ',
            'dematerialise_ciel' => "J'adhère à la dématérialisation de la DRM avec les douanes CIEL et je suis inscrit et identifié sur Prodouane."
		));
		$this->setValidators(array(
			'nom' => new sfValidatorString(array('required' => true)),
			'prenom' => new sfValidatorString(array('required' => true)),
			'fonction' => new sfValidatorString(array('required' => true)),
			'telephone' => new sfValidatorString(array('required' => true)),
			'fax' => new sfValidatorString(array('required' => false)),
            'email' => new sfValidatorEmailStrict(array('required' => true)),
        	'email2' => new sfValidatorEmailStrict(array('required' => true)),
        	'dematerialise_ciel' => new ValidatorBoolean(array('required' => false))
		));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('email', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'email2',
                                                                             array(),
                                                                             array('invalid' => 'Les adresses e-mail doivent être identique')));
		
		$formEtablissements = new ContratEtablissementCollectionForm($this->getObject(), array(), array(
	    	'nbEtablissement'    => $this->getOption('nbEtablissement', 1)
	  	));
  		$this->embedForm('etablissements', $formEtablissements);

        $this->widgetSchema->setNameFormat('contrat[%s]');
        

    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if ($this->getObject()->isNew())
        {
            $noContrat = ContratClient::getInstance()->getNextNoContrat();
            $this->getObject()->set('_id', 'CONTRAT-'.$noContrat);
            $this->getObject()->setNoContrat($noContrat);
        }
    }
    
    protected function updateDefaultsFromObject() {
		parent::updateDefaultsFromObject();
		$defaults = $this->getDefaults();
		$defaults['email2'] = $defaults['email'];
		$etablissements = $defaults['etablissements'];
		foreach ($etablissements as $key => $value) {
			$value['siret_cni'] = ($value['siret'])? $value['siret'] : $value['cni'];
			$defaults['etablissements'][$key] = $value;
		}
		$this->setDefaults($defaults);
    }

}