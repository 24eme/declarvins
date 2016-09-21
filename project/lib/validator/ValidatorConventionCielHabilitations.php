<?php

class ValidatorConventionCielHabilitations extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('nb_utilisateur', "Vous pouvez saisir jusqu'à six utilisateurs informatiquement. Au delà, merci de bien vouloir renseigner les suivants sur papier libre en annexe de la convention.");
        $this->addMessage('no_utilisateur', "Vous devez saisir au moins un utilisateur.");
        $this->addMessage('no_accises', "Vous devez renseigner le numéro accises pour les utilisateurs habilités à la téléprocédure CIEL.");
        $this->addMessage('habilitation', "Vous devez renseigner les habilitations CIEL.");
        $this->addMessage('telepaiement', "Vous devez renseigner au moins un utilisateur habilité au télépaiement CIEL.");
    }

    protected function doClean($values) {
    	if (!count($values['habilitations'])) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'no_utilisateur')));
    	}
    	if (count($values['habilitations']) > 5) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'nb_utilisateur')));
    	}
    	$nbTeleprocedure = 0;
    	$nbTelepaiement = 0;
    	foreach ($values['habilitations'] as $habilitation) {
    		if ($habilitation['droit_teleprocedure'] && !$habilitation['no_accises']) {
    			throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'no_accises')));
    		}
    		if ($habilitation['droit_teleprocedure']) {
    			$nbTeleprocedure++;
    		}
    		if ($habilitation['droit_telepaiement']) {
    			$nbTelepaiement++;
    		}
    	}
    	
    	if (!($nbTeleprocedure + $nbTelepaiement)) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'habilitation')));
    	}
    	
    	if (!$nbTelepaiement) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'telepaiement')));
    	}

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        return $values;
    }

}
