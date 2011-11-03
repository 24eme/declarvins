<?php

class ValidatorXorSiretCni extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('siret_field', 'siret');
        $this->addOption('cni_field', 'cni');
        $this->addOption('throw_global_error', false);
		$this->addMessage('both', 'LE SIRET est renseigné, vous ne pouvez pas fournir de CNI');
        $this->addMessage('xor', 'Vous devez renseigner le SIRET ou le CNI');
    }

    protected function doClean($values) {   	
        if (!empty($values['siret']) && !empty($values['cni'])) {
        	$field = 'cni_field';
        	$message = 'both';
        }
        elseif (empty($values['siret']) && empty($values['cni'])) {
        	$field = 'siret_field';
        	$message = 'xor';
        }
        else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption($field) => new sfValidatorError($this, $message)));
    }

}
