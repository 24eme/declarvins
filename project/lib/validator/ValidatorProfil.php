<?php

class ValidatorProfil extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('mdp_field', 'mdp');
        $this->addOption('throw_global_error', false);

        $this->setMessage('invalid', 'Mot de passe incorrect');
    }

    protected function doClean($values) {
        if ($values['mdp']) {
        	if ($values['mdp1']) {
            	$compte = _CompteClient::getInstance()->retrieveByLogin($values['login']);
            	if (acVinCompte::compareMotDePasseSSHA($compte->mot_de_passe, $values['mdp'])) {
                	return $values;
            	}
        	}
        }
        elseif (!$values['mdp1']) {
            return $values;
        	
        }
        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('mdp_field') => new sfValidatorError($this, 'invalid')));
    }

}
