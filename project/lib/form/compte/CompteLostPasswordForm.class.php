<?php
class CompteLostPasswordForm extends BaseForm {
    
    
    public function configure() {
        $this->setWidgets(array(
        		'login' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
        		'login' => 'Identifiant ou e-mail de votre compte*: '
        ));

        $this->setValidators(array(
        		'login' => new sfValidatorString(array('required' => true))
        ));
        
		$this->widgetSchema->setNameFormat('compte_lost_password[%s]');
		$this->mergePostValidator(new ValidatorLoginCompteExist());
    }
}
