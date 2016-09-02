<?php
class CielAssistanceForm extends BaseForm {

    public function configure() {
    	$this->setWidgets(array(
			'nom' => new sfWidgetFormInputText(),
			'prenom' => new sfWidgetFormInputText(),
			'telephone' => new sfWidgetFormInputText(),
			'email' => new sfWidgetFormInputText(),
			'sujet' => new sfWidgetFormInputText(),
			'message' => new sfWidgetFormTextarea()
    					
    	));
    	$this->setValidators(array(
    			'nom' => new sfValidatorString(array('required' => true)),
    			'prenom' => new sfValidatorString(array('required' => true)),
    			'telephone' => new sfValidatorRegex(array('pattern' => '/^0[0-9]{9}/', 'required' => false)),
    			'email' => new sfValidatorEmailStrict(array('required' => false)),
    			'sujet' => new sfValidatorString(array('required' => true)),
    			'message' => new sfValidatorString(array('required' => true))
    	));
    	
		$this->widgetSchema->setLabels(array(
			'nom' => 'Nom*: ',
			'prenom' => 'Prénom*: ',
			'telephone' => 'Téléphone: ',
            'email' => 'Adresse e-mail: ',
			'sujet' => 'Sujet*:',
			'message' => 'Message*:'
		));

        $this->widgetSchema->setNameFormat('ciel_assistance[%s]');
        

    }

}