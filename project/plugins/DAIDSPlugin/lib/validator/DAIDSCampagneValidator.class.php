<?php

class DAIDSCampagneValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "Vous ne pouvez pas créer une DAIDS future.");
        $this->setMessage('invalid', "Cette DAIDS existe déjà.");
        $this->setMessage('required', "Champs obligatoires.");
        $this->addRequiredOption('etablissement');
    }

    protected function doClean($values) {
        if (!$values['campagne']) {
        	throw new sfValidatorErrorSchema($this, array('campagne' => new sfValidatorError($this, 'required')));
        }       
        if (DAIDSClient::getInstance()->formatToCompare($values['campagne']) > DAIDSClient::getInstance()->formatToCompare(DAIDSClient::getInstance()->getCurrentPeriode())) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'impossible')));
        } 
        $daids = DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->getOption('etablissement'), $values['campagne']);
        if ($daids) {
            throw new sfValidatorErrorSchema($this, array('campagne' => new sfValidatorError($this, 'invalid')));
        }       
        return $values;
    }

}
