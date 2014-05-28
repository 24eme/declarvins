<?php
abstract class CompteForm extends acCouchdbObjectForm {
    
    
    public function configure() {
        $this->setWidgets(array(
        		'login' => new sfWidgetFormInputText(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword()
        ));
        $this->widgetSchema->setLabels(array(
        		'login' => 'Identifiant*: ',
                'mdp1'  => 'Mot de passe*: ',
                'mdp2'  => 'Vérification du mot de passe*: '
        ));

        $this->setValidators(array(
        		'login' => new sfValidatorString(array('required' => true, 'min_length' => 6)),
                'mdp1'  => new sfValidatorString(array('required' => true, "min_length" => 4)),
                'mdp2'  => new sfValidatorString(array('required' => true))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identique')));
		$this->widgetSchema->setNameFormat('compte[%s]');
		$this->mergePostValidator(new ValidatorLoginCompte());
    }
}
