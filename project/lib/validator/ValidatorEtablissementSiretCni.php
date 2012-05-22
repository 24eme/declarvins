<?php

class ValidatorEtablissementSiretCni extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('siret_cni_field', 'siret_cni');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Ce numéro n\'est pas valide');
    }

    protected function doClean($values) {
        if ($siret_cni = $values['siret_cni']) {
            $size = strlen($siret_cni);
            if ($size == 14) {
                $values['siret'] = $values['siret_cni'];
            	return $values;
            } elseif($size == 12) {
                $values['cni'] = $values['siret_cni'];
            	return $values;
            }
        }
        else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('siret_cni_field') => new sfValidatorError($this, 'invalid')));
    }

}
