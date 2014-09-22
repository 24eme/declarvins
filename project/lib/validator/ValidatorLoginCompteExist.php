<?php

class ValidatorLoginCompteExist extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('login_field', 'login');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Cet identifiant n\'existe pas');
    }

    protected function doClean($values) {
    	if (isset($values['login']) && !empty($values['login'])) {
    		$logins = array();
            if ($compte = acCouchdbManager::getClient('_Compte')->retrieveByLogin($values['login'])) {
            	$values['logins'] = array($values['login']);
                return $values;
            }
            $comptes = CompteEmailView::getInstance()->findByEmail($values['login'])->rows;
            $login = null;
            foreach ($comptes as $compte) {
            	if ($l = $compte->value[CompteEmailView::VALUE_LOGIN]) {
            		$login = $l;
            		$logins[] = $l;
            	}
            }
            if ($login) {
            	$values['login'] = $login;
            	$values['logins'] = $logins;
            	return $values;
            }
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
