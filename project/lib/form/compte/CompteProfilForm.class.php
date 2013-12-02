<?php
class CompteProfilForm extends acCouchdbObjectForm {
   
     public function configure() 
     {
         $this->setWidgets(array(
                'mdp'  => new sfWidgetFormInputPassword(),
                'mdp1'  => new sfWidgetFormInputPassword(),
                'mdp2'  => new sfWidgetFormInputPassword(),
         		'login' => new sfWidgetFormInputHidden()
        ));

        $this->widgetSchema->setLabels(array(
                'mdp'  => 'Ancien mot de passe: ',
                'mdp1'  => 'Nouveau mot de passe: ',
                'mdp2'  => 'Vérification du nouveau mot de passe: ',
        ));


        $this->setValidators(array(
        		'mdp'  => new sfValidatorString(array('required' => false, "min_length" => "4")),
                'mdp1'  => new sfValidatorString(array('required' => false, "min_length" => "4")),
                'mdp2'  => new sfValidatorString(array('required' => false)),
        		'login' => new sfValidatorString(array('required' => true))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('mdp1', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'mdp2',
                                                                             array(),
                                                                             array('invalid' => 'Les mots de passe doivent être identiques')));
        $this->mergePostValidator(new ValidatorProfil());
        
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
