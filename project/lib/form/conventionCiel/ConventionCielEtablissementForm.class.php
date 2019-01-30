<?php
class ConventionCielEtablissementForm extends acCouchdbObjectForm 
{
	
    public function configure() {
        
        $this->setWidgets(array(
	       'cvi' => new sfWidgetFormInputText(),
	       'no_accises' => new sfWidgetFormInputText()
        ));
        $this->widgetSchema->setLabels(array(
	       'cvi' => 'CVI*: ',
	       'no_accises' => 'Numéro accises*: ',
        ));
        $this->setValidators(array(
        		'cvi' => new sfValidatorString(array('required' => true, 'max_length' => 11, 'min_length' => 9)),
        		'no_accises' => new sfValidatorRegex(array('required' => true, 'pattern' => '/^FR[a-zA-Z0-9]{11}$/')),
        ));
		$this->widgetSchema->setNameFormat('etablissement[%s]');
    }
	
}