<?php

class DRMCampagneValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('impossible', "Vous ne pouvez pas créer une DRM future.");
        $this->addMessage('campagne', "Vous ne pouvez pas créer une DRM pour cette campagne car il n'y a pas de DAI/DS déclarée pour la campagne précédente.");
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
        $values['campagne'] = DRMClient::getInstance()->buildCampagne($periode);
        $annee = preg_replace('/([0-9]{4})-([0-9]{4})/', '$1', $values['campagne']);
        $campagnePrecedente = ($annee-1).'-'.$annee;
        $daids = DAIDSClient::getInstance()->findMasterByIdentifiantAndPeriode($this->identifiant, $campagnePrecedente);
        if (!$daids) {
        	throw new sfValidatorErrorSchema($this, array($this->getOption('years') => new sfValidatorError($this, 'campagne')));
        }
        return $values;
    }

}
