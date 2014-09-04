<?php
class CompteLostPasswordForm extends BaseForm {
    
    
    public function configure() {
        $this->setWidgets(array(
        		'login' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
        		'login' => 'Identifiant*: '
        ));

        $this->setValidators(array(
        		'login' => new sfValidatorRegex(array('required' => true, 'pattern' => '/^([a-z0-9\-\_\@\.]*)$/', 'min_length' => 6))
        ));
        
		$this->widgetSchema->setNameFormat('compte_lost_password[%s]');
		$this->mergePostValidator(new ValidatorLoginCompteExist());
    }
}
