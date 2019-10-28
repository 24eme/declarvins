<?php

class VracProduitValidator extends sfValidatorBase {

    public function configure($options = array(), $messages = array()) {
        $this->addMessage('format_millesime', "Le millésime doit être renseigné sur quatre chiffres");
        $this->addMessage('date_millesime', "Millésime invalide");
        $this->addMessage('millesime_inexistant', "Le millésime doit être renseigné si la case Non millésimé n'est pas cochée.");
    }

    protected function doClean($values) {
        $errorSchema = new sfValidatorErrorSchema($this);
        $hasError = false;
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
