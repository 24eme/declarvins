<?php

class ValidatorLoginCompte extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('login_field', 'login');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Cet identifiant n\'est pas disponible');
    }

    protected function doClean($values) {
        if ($values['login']) {
            $compte = sfCouchdbManager::getClient('_Compte')->retrieveByLogin($values['login']);
            if (!$compte)
                return $values;
        }
        else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('login_field') => new sfValidatorError($this, 'invalid')));
    }

}
