<?php
abstract class CompteForm extends sfCouchdbFormDocumentJson {
    
    
    public function configure() {
        $this->setWidgets(array(
        		'login' => new sfWidgetFormInputText(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword(),
                'email' => new sfWidgetFormInputText(),
        		'email2' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
        		'login' => 'Identifiant*: ',
                'mdp1'  => 'Mot de passe*: ',
                'mdp2'  => 'Vérification du mot de passe*: ',
                'email' => 'Adresse e-mail*: ',
                'email2' => 'Vérification de l\'e-mail*: '
        ));

        $this->setValidators(array(
        		'login' => new sfValidatorRegex(array('required' => true, 'pattern' => '/^([a-zA-Z0-9\-_]*)$/', 'min_length' => 6),array('required' => 'Champ obligatoire', 'invalid' => 'Identifiant invalide.', 'min_length' => '6 caractères minimum.')),
                'mdp1'  => new sfValidatorString(array('required' => true, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp2'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
                'email' => new sfValidatorEmailStrict(array('required' => true),array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.')),
        		'email2' => new sfValidatorEmailStrict(array('required' => true),array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.'))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identique')));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('email', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'email2',
                                                                             array(),
                                                                             array('invalid' => 'Les adresses e-mail doivent être identique')));
		$this->widgetSchema->setNameFormat('compte[%s]');
		$this->mergePostValidator(new ValidatorLoginCompte());
    }
}
