<?php
class CompteModificationForm extends sfCouchdbFormDocumentJson {
    
    /**
     * 
     */
    public function configure() {
        $this->setWidgets(array(
        		'nom'  => new sfWidgetFormInputText(),
        		'prenom'  => new sfWidgetFormInputText(),
        		'telephone'  => new sfWidgetFormInputText(),
        		'fax'  => new sfWidgetFormInputText(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword(),
                'email' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
		        'nom'  => 'Nom*: ',
		        'prenom'  => 'Prénom*: ',
		        'telephone'  => 'Téléphone: ',
		        'fax'  => 'Fax: ',
                'mdp1'  => 'Mot de passe*: ',
                'mdp2'  => 'Vérification du mot de passe*: ',
                'email' => 'Adresse e-mail*: '
        ));

        $this->setValidators(array(
		        'nom'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
		        'prenom'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
		        'telephone'  => new sfValidatorString(array('required' => false)),
        		'fax'  => new sfValidatorString(array('required' => false)),
                'mdp1'  => new sfValidatorString(array('required' => false, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp2'  => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire')),
                'email' => new sfValidatorEmailStrict(array('required' => true),array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.'))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identique')));
		$this->widgetSchema->setNameFormat('compte[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        if (!$this->getObject()->isNew())
        {
            $this->getObject()->setPasswordSSHA($values['mdp1']);
        }
    }
    
}
