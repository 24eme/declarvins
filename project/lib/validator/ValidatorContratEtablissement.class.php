<?php

class ValidatorContratEtablissement extends sfValidatorSchema {

    public function configure($options = array(), $messages = array()) {
        $this->addOption('cvi_field', 'cvi');
        $this->addOption('no_accises_field', 'no_accises');
        $this->addOption('no_tva_intracommunautaire_field', 'no_tva_intracommunautaire');
    }

    protected function doClean($values) {
        if ($values['famille'] == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
                if (!isset($values['cvi_field']) || empty($values['cvi_field'])) {
                	throw new sfValidatorErrorSchema($this, array($this->getOption('cvi_field') => new sfValidatorError($this, 'required')));
                }
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	throw new sfValidatorErrorSchema($this, array($this->getOption('no_accises_field') => new sfValidatorError($this, 'required')));
                }
        }
    
        if ($values['famille'] == EtablissementFamilles::FAMILLE_NEGOCIANT) {
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	throw new sfValidatorErrorSchema($this, array($this->getOption('no_accises_field') => new sfValidatorError($this, 'required')));
                }
                if (!isset($values['no_tva_intracommunautaire']) || empty($values['no_tva_intracommunautaire'])) {
                	throw new sfValidatorErrorSchema($this, array($this->getOption('no_tva_intracommunautaire_field') => new sfValidatorError($this, 'required')));
                }
        }

        return $values;
    }

}
