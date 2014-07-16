<?php
class CompteSelectionForm extends sfForm {
	
	public function configure() {
    	$this->setWidgets(array(
			'compte' => new sfWidgetFormChoice(array('choices' => array()), array('data-ajax' => $this->getUrlAutocomplete())) 		
    	));
		$this->widgetSchema->setLabels(array(
			'compte' => 'Compte*: ',
		));
		$this->setValidators(array(
			'compte' => new ValidatorPass(),
		));
        $this->widgetSchema->setNameFormat('compte_selection[%s]');
    }
    
    public function setName($name)
    {
    	$name = ($name)? $name : 'compte_selection';
    	$this->widgetSchema->setNameFormat($name.'[%s]');
    }

    public function getUrlAutocomplete() {

        return sfContext::getInstance()->getRouting()->generate('compte_autocomplete');
    }
    
}