<?php
class DRMLabelValidator extends sfValidatorSchema 
{
    public function configure($options = array(), $messages = array()) 
    {
        $this->addOption('label_supplementaire_field', 'label_supplementaire');
        $this->addOption('throw_global_error', false);
        $this->setMessage('invalid', 'Si vous ne selectionnez pas de label, vous devez spécifier un label supplémentaire');
    }

    protected function doClean($values) 
    {
    	
        if ((isset($values['label']) && !empty($values['label'])) || (isset($values['label_supplementaire']) && !empty($values['label_supplementaire']))) {
			return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }
        throw new sfValidatorErrorSchema($this, array($this->getOption('label_supplementaire_field') => new sfValidatorError($this, 'invalid')));
    }
}