<?php

class ValidatorContratDouane extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('service_douane_field', 'service_douane');
    }

    protected function doClean($values) {
        if ($values['famille'] == EtablissementFamilles::FAMILLE_COURTIER) {
                return $values;
        }
        if (isset($values['service_douane']) && !empty($values['service_douane'])) {
        	return $values;
        }

        throw new sfValidatorErrorSchema($this, array($this->getOption('service_douane_field') => new sfValidatorError($this, 'required')));
    }

}
