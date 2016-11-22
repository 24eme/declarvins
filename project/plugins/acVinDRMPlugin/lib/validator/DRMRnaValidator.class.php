<?php

class DRMRnaValidator extends sfValidatorSchema 
{

    public function configure($options = array(), $messages = array()) 
    {
        $this->addOption('organisme_field', 'organisme');
    }

    protected function doClean($values) 
    {
    	if (!$values['numero'] && ($values['accises'] || $values['date'])) {
    		throw new sfValidatorErrorSchema($this, array('numero' => new sfValidatorError($this, 'required')));
    	}
        return $values;
        
    }

}
