<?php

class DAIDSInformationsForm extends BaseForm 
{

    public function configure() {
    	if ($this->getOption('is_admin')) {
	        $this->setWidgets(array(
	            'confirmation' => new sfWidgetFormInputHidden()
	        ));
	        $this->setValidators(array(
	            'confirmation' => new ValidatorPass()
	        ));
	        $this->setDefault('confirmation', 'confirmation');
    		
    	} else {
	        $this->setWidgets(array(
	            'confirmation' => new sfWidgetFormChoice(array(
	                'expanded' => true,
	                'choices' => array(
	                    'confirmation' => "Je confirme l'exactitude de ces informations",
	                    'modification' => "Je souhaite modifier mes informations de structure"
	                ),
	                'renderer_options' => array('formatter' => array($this, 'formatter'))
	            ))
	        ));
	        $this->setValidators(array(
	            'confirmation' => new sfValidatorChoice(array('required' => true, 'choices' => array('confirmation', 'modification')))
	        ));
    	}
        
        $this->validatorSchema['confirmation']->setMessage('required', 'Merci de confirmer vos informations.');
        $this->widgetSchema->setNameFormat('daids_informations[%s]');
    }

    public function formatter($widget, $inputs) {
        $rows = array();
        foreach ($inputs as $input) {
            $rows[] = $widget->renderContentTag('div', $input['label'] . $this->getOption('label_separator') . $input['input'], array('class' => 'ligne_form'));
        }

        return!$rows ? '' : implode($widget->getOption('separator'), $rows);
    }

}