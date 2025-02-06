<?php

class ValidatorLoginCompteExist extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('login_field', 'login');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Cet identifiant n\'existe pas');
    }

    protected function doClean($values) {
    	if (isset($values['login']) && !empty($values['login'])) {
            if (acCouchdbManager::getClient('_Compte')->retrieveByLogin($values['login'])) {
                return $values;
            }
            foreach (CompteEmailView::getInstance()->findByEmail($values['login'])->rows as $row) {
                if (_CompteClient::getInstance()->retrieveByLogin($row->value[CompteEmailView::VALUE_LOGIN])) {
                    return $values;
                }
            }
        } else {
            return $values;
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('login_field') => new sfValidatorError($this, 'invalid')));
    }

}
