<?php
class CompteProfilForm extends acCouchdbObjectForm {
   
     public function configure() 
     {
         $this->setWidgets(array(
                'email' => new sfWidgetFormInputText(),
                'mdp'  => new sfWidgetFormInputPassword(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword(),
         		'nom' => new sfWidgetFormInputText(),
         		'prenom' => new sfWidgetFormInputText(),
         		'telephone' => new sfWidgetFormInputText(),
         		'fax' => new sfWidgetFormInputText(),
         		'login' => new sfWidgetFormInputHidden()
        ));

        $this->widgetSchema->setLabels(array(
                'email' => 'Adresse e-mail: ',
                'mdp'  => 'Ancien mot de passe: ',
                'mdp1'  => 'Nouveau mot de passe: ',
                'mdp2'  => 'Vérification du nouveau mot de passe: ',
                'nom'  => 'Nom*: ',
                'prenom'  => 'Prénom: ',
                'telephone'  => 'Téléphone: ',
                'fax'  => 'Fax: '
        ));


        $this->setValidators(array(
                'email' => new sfValidatorEmail(array('required' => true),array('required' => 'Champ obligatoire', 'invalid' => 'Adresse email invalide.')),
        		'mdp'  => new sfValidatorString(array('required' => false, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp1'  => new sfValidatorString(array('required' => false, "min_length" => "4"), array('required' => 'Champ obligatoire', "min_length" => "Votre mot de passe doit comporter au minimum 4 caractères.")),
                'mdp2'  => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire')),
                'nom' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
        		'prenom' => new sfValidatorString(array('required' => false), array('required' => 'Champ obligatoire')),
        		'telephone' => new sfValidatorString(array('required' => false)),
        		'fax' => new sfValidatorString(array('required' => false)),
        		'login' => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire'))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identiques')));
        $this->mergePostValidator(new ValidatorProfil());
         
        $this->getWidget('email')->setAttribute('readonly','readonly');
        
        $this->widgetSchema->setNameFormat('profil[%s]');
       
         
     }
     
     public function doUpdateObject($values) 
     {
		parent::doUpdateObject($values);
        if($values['mdp1']) {
        	$this->getObject()->setMotDePasseSSHA($values['mdp1']);
        }
	}

}

?>
