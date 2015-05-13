<?php

class DRMCampagneValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "Vous ne pouvez pas créer une DRM future.");
        $this->setMessage('invalid', "Cette DRM existe déjà.");
        $this->setMessage('required', "Champs obligatoires.");
        $this->addRequiredOption('etablissement');
    }

    protected function doClean($values) {
        if (!$values['months']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('months') => new sfValidatorError($this, 'required')));
        }
        if (!$values['years']) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'required')));
        }
        $periode = sprintf('%04d%02d', $values['years'], $values['months']);
        if ($periode > date('Ym')) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'impossible')));
        }
        
        $drm = DRMClient::getInstance()->findMasterByIdentifiantAndPeriode($this->getOption('etablissement'), $periode);
        if ($drm) {
            throw new sfValidatorErrorSchema($this, array($this->getOption('months') => new sfValidatorError($this, 'invalid')));
        }       
        $values['campagne'] = sprintf('%04d-%02d', $values['years'], $values['months']);
        return $values;
    }

}
