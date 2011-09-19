<?php

class ValidatorIdentifiantChai extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('identifiant_field', 'identifiant');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Cet identifiant n\'est pas disponible');
    }

    protected function doClean($values) {
        if ($values['identifiant']) {
            $tiers = sfCouchdbManager::getClient('Chai')->retrieveByIdentifiant($values['identifiant']);
            if (!$tiers)
                return $values;
        }
        else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('identifiant_field') => new sfValidatorError($this, 'invalid')));
    }

}
