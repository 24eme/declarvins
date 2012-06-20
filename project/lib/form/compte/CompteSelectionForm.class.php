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
			'compte' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->getComptes()))),
		));
        $this->widgetSchema->setNameFormat('compte_selection[%s]');
    }
    
    private function getComptes() {
    	$comptes = _CompteClient::getInstance()->findAll();
    	$result = array('' => '');
    	foreach ($comptes->rows as $compte) {
    		$result[$compte->key[3]] = _CompteClient::getInstance()->makeLibelle($compte->key); 
    	}
    	return $result;
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