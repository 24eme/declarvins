<?php

class ValidatorContrat extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('contrat_field', 'contrat');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Ce contrat n\'existe pas');
    }

    protected function doClean($values) {
        if ($values['contrat']) {
            $contrat = sfCouchdbManager::getClient('Contrat')->retrieveById($values['contrat']);
            if ($contrat)
                return $values;
        }
        else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('contrat_field') => new sfValidatorError($this, 'invalid')));
    }

}
