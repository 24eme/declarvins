<?php

class ValidatorContratEtablissement extends sfValidatorSchema {


    protected function doClean($values) {
        if ($values['famille'] == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
                if (!isset($values['cvi']) || empty($values['cvi'])) {
                	throw new sfValidatorErrorSchema($this, array('cvi' => new sfValidatorError($this, 'required')));
                }
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	throw new sfValidatorErrorSchema($this, array('no_accises' => new sfValidatorError($this, 'required')));
                }
        }
    
        if ($values['famille'] == EtablissementFamilles::FAMILLE_NEGOCIANT) {
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	throw new sfValidatorErrorSchema($this, array('no_accises' => new sfValidatorError($this, 'required')));
                }
                if (!isset($values['no_tva_intracommunautaire']) || empty($values['no_tva_intracommunautaire'])) {
                	throw new sfValidatorErrorSchema($this, array('no_tva_intracommunautaire' => new sfValidatorError($this, 'required')));
                }
        }

        return $values;
    }

}
