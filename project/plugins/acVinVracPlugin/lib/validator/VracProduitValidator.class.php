<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('format_millesime', "Le millésime doit être renseigné sur quatre chiffres");
        $this->addMessage('date_millesime', "Millésime invalide");
        $this->addMessage('millesime_inexistant', "Le millésime doit être renseigné si la case Non millésimé n'est pas cochée.");
        $this->addMessage('labels_incoherent', "Combinaison de label impossible.");
    }

    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);
        $hasError = false;
        if (isset($values['labels_arr']) && $values['labels_arr']) {
            if (
                (in_array('conv', $values['labels_arr']) && in_array('biol', $values['labels_arr'])) ||
                (in_array('conv', $values['labels_arr']) && in_array('bioc', $values['labels_arr'])) ||
                (in_array('biol', $values['labels_arr']) && in_array('bioc', $values['labels_arr'])) ||
                (in_array('conv', $values['labels_arr']) && in_array('biol', $values['labels_arr']) && in_array('bioc', $values['labels_arr']))
                ) {
                    $errorSchema->addError(new sfValidatorError($this, 'labels_incoherent'), 'labels_arr');
                    $hasError = true;
                
            }
        }
        if (!isset($values['millesime']) || empty($values['millesime'])) {
                if (isset($values['non_millesime']) && !$values['non_millesime']) {
                        $errorSchema->addError(new sfValidatorError($this, 'millesime_inexistant'), 'millesime');
                        $hasError = true;
                }
        }

        if (isset($values['millesime']) && !empty($values['millesime'])) {
                if (strlen($values['millesime']) != 4) {
                                        $errorSchema->addError(new sfValidatorError($this, 'format_millesime'), 'millesime');
                                        $hasError = true;
                }
                if ($values['millesime'] > (date('Y')+1)) {
                                        $errorSchema->addError(new sfValidatorError($this, 'date_millesime'), 'millesime');
                                        $hasError = true;
                }
        }
        if ($hasError) {
                throw new sfValidatorErrorSchema($this, $errorSchema);
        }
        return $values;
    }

}
