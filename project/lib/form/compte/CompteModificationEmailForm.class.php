<?php
class CompteModificationEmailForm extends acCouchdbObjectForm {
    
    /**
     * 
     */
    public function configure() {
        $this->setWidgets(array(
                'email' => new sfWidgetFormInputText(),
        		'email2' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
                'email' => 'Adresse e-mail*: ',
                'email2' => 'Vérification de l\'e-mail*: '
        ));

        $this->setValidators(array(
                'email' => new sfValidatorEmailStrict(array('required' => true)),
        		'email2' => new sfValidatorEmailStrict(array('required' => true))
        ));
        
        $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('email', 
                                                                             sfValidatorSchemaCompare::EQUAL, 
                                                                             'email2',
                                                                             array(),
                                                                             array('invalid' => 'Les adresses e-mail doivent être identique')));
		$this->widgetSchema->setNameFormat('compte[%s]');
    }
    
}
