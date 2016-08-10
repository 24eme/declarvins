<?php

class ValidatorConventionCielHabilitations extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('nb_utilisateur', "Vous pouvez saisir jusqu'à six utilisateurs informatiquement. Au delà, merci de bien vouloir renseigner les suivants sur papier libre en annexe de la convention.");
    }

    protected function doClean($values) {
    	if (count($values['habilitations']) > 5) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'nb_utilisateur')));
    	}

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        return $values;
    }

}
