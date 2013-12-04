<?php

class ValidatorContratEtablissement extends sfValidatorSchema {


    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasErrors = false;
        if ($values['famille'] == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
                if (!isset($values['cvi']) || empty($values['cvi'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('cvi' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('no_accises' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
        }
    
        if ($values['famille'] == EtablissementFamilles::FAMILLE_NEGOCIANT) {
                if (!isset($values['no_accises']) || empty($values['no_accises'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('no_accises' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
                if (!isset($values['no_tva_intracommunautaire']) || empty($values['no_tva_intracommunautaire'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('no_tva_intracommunautaire' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
        }
    
        if ($values['famille'] == EtablissementFamilles::FAMILLE_COURTIER) {
                if (!isset($values['no_carte_professionnelle']) || empty($values['no_carte_professionnelle'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('no_carte_professionnelle' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
        }
        
        if ($hasErrors) {
        	throw $errorSchema;
        }

        return $values;
    }

}
