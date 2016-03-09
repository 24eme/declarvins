<?php

class DRMDeclaratifValidator extends sfValidatorSchema 
{

    public function configure($options = array(), $messages = array()) 
    {
        $this->addOption('organisme_field', 'organisme');
    }

    protected function doClean($values) 
    {
        if ($values['caution'] == 0 && $values['organisme']) {
                return $values;
        }
        elseif ($values['caution'] == 1 && $values['numero']) {
            return $values;
        }
        if ($values['caution'] == 0 && !$values['organisme']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('organisme_field') => new sfValidatorError($this, 'required')));
        }
        if ($values['caution'] == 1 && !$values['numero']) {
        	throw new sfValidatorErrorSchema($this, array('numero' => new sfValidatorError($this, 'required')));
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'required');
        }

        
    }

}
