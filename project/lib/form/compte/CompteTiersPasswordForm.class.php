<?php
class CompteTiersPasswordForm extends acCouchdbObjectForm {
	
	public function configure() {
        $this->setWidgets(array(
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword()
        ));
        $this->widgetSchema->setLabels(array(
                'mdp1'  => 'Mot de passe*: ',
                'mdp2'  => 'Vérification du mot de passe*: '
        ));

        $this->setValidators(array(
                'mdp1'  => new sfValidatorString(array('required' => true, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp2'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identique')));
		$this->widgetSchema->setNameFormat('compte_password[%s]');
    }
    
    public function doUpdateObject($values) {
        parent::doUpdateObject($values);
        $this->getObject()->setMotDePasseSSHA($values['mdp1']);
    }

}