<?php

class VracProduitIrValidator extends VracProduitValidator {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('labels_incoherent', "Combinaison de label impossible.");
    }

    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);
        $hasError = false;
        
        if (isset($values['labels_arr']) && in_array('autre', $values['labels_arr']) && !$values['labels_libelle_autre']) {
            $errorSchema->addError(new sfValidatorError($this, 'required'), 'labels_libelle_autre');
            $hasError = true;
        }
        
        if (isset($values['mentions']) && in_array('chdo', $values['mentions']) && !$values['mentions_libelle_chdo']) {
            $errorSchema->addError(new sfValidatorError($this, 'required'), 'mentions_libelle_chdo');
            $hasError = true;
        }
        
        if (isset($values['mentions']) && in_array('marque', $values['mentions']) && !$values['mentions_libelle_marque']) {
            $errorSchema->addError(new sfValidatorError($this, 'required'), 'mentions_libelle_marque');
            $hasError = true;
        }
        
        if (isset($values['mentions']) && in_array('autre', $values['mentions']) && !$values['mentions_libelle_autre']) {
            $errorSchema->addError(new sfValidatorError($this, 'required'), 'mentions_libelle_autre');
            $hasError = true;
        }

        if ($hasError) {
                throw new sfValidatorErrorSchema($this, $errorSchema);
        }
        return $values;
    }

}
