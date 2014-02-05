<?php

class ValidatorContratEtablissement extends sfValidatorSchema {


    public function __construct($fields = null, $options = array(), $messages = array())
    {
        $this->addRequiredOption('no_carte_professionnelle');
        $this->addRequiredOption('famille');
        
        $this->no_carte_professionnelle = $options['no_carte_professionnelle'];        
        $this->famille = $options['famille'];
        
        $this->addMessage('no_carte_professionnelle_wrong_famille',$messages['no_carte_professionnelle_wrong_famille']);
        
        
        parent::__construct($fields,$options, $messages);
        $this->addOption('throw_global_error', false);
        
    }
    
    protected function doClean($values) {
    	$errorSchema = new sfValidatorErrorSchema($this);
    	$hasErrors = false;
        if ($values['famille'] == EtablissementFamilles::FAMILLE_PRODUCTEUR) {
                if (!isset($values['cvi']) || empty($values['cvi'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array('cvi' => new sfValidatorError($this, 'required'))));
                	$hasErrors = true;
                }
                
        }
    
        if ($values['famille'] == EtablissementFamilles::FAMILLE_NEGOCIANT) {
                
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
        
        if ($values['famille'] != EtablissementFamilles::FAMILLE_COURTIER) {
            if (isset($values['no_carte_professionnelle']) && !empty($values['no_carte_professionnelle'])) {
                	$errorSchema->addError(new sfValidatorErrorSchema($this, array($this->no_carte_professionnelle => new sfValidatorError($this, $this->messages['no_carte_professionnelle_wrong_famille']))));
                	$hasErrors = true;
                }
        }
        
        if ($hasErrors) {
        	throw $errorSchema;
        }

        return $values;
    }

}
