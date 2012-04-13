<?php
class LoginContratForm extends BaseForm {
    
    public function configure() {
        $this->setWidgets(array(
                'contrat' => new sfWidgetFormInputText(),
        ));

        $this->widgetSchema->setLabels(array(
                'contrat' => 'Contrat numéro : '
        ));

        $this->setValidators(array(
                'contrat'  => new sfValidatorString(array('required' => true), array('required' => 'Champ obligatoire')),
        ));
        
        $this->mergePostValidator(new ValidatorContrat());
        $this->widgetSchema->setNameFormat('login_contrat[%s]');
    }
}

