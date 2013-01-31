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
        		'login' => new sfValidatorRegex(array('required' => true, 'pattern' => '/^([a-zA-Z0-9\-_]*)$/'),array('required' => 'Champ obligatoire', 'invalid' => 'Identifiant invalide.'))
        ));
        
		$this->widgetSchema->setNameFormat('compte_lost_password[%s]');
		$this->mergePostValidator(new ValidatorLoginCompteExist());
    }
}
