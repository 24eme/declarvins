<?php

class ValidatorConventionCiel extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('nb_etablissement', "Vous ne pouvez pas déclarer plus de cinq chais pour adhérer. Merci de prendre contact avec votre interprofession.");
    }

    protected function doClean($values) {
    	if (count($values['etablissements']) > 5) {
    		throw new sfValidatorErrorSchema($this, array('' => new sfValidatorError($this, 'nb_etablissement')));
    	}

        if ($this->getOption('throw_global_error')) {
            throw new sfValidatorError($this, 'invalid');
        }

        return $values;
    }

}
