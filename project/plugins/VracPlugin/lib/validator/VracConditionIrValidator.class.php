<?php

class VracConditionIrValidator extends VracConditionValidator {

    public function configure($options = array(), $messages = array()) {
        parent::configure($options, $messages);
        $this->addMessage('cas_particulier_type_transaction', "L'apport contractuel à une union n'est possible que pour les contrats de type vin");
        $this->addMessage('cas_particulier_vendeur_union', "L'apport contractuel à une union n'est possible que pour les vendeurs producteur / union");
    }

    protected function doClean($values) {
        parent::doClean($values);
        $errorSchema = new sfValidatorErrorSchema($this);
        $hasError = false;

        $cas_particulier = $values['cas_particulier'] ?? null;
        $type_transaction = $values['type_transaction'] ?? null;

        if ($cas_particulier == 'union' && $type_transaction != 'vrac') {
            $errorSchema->addError(new sfValidatorError($this, 'cas_particulier_type_transaction'), 'cas_particulier');
            $hasError = true;
        }
        if ($cas_particulier == 'union' && $this->vrac && $this->vrac->vendeur->sous_famille != EtablissementFamilles::SOUS_FAMILLE_UNION) {
            $errorSchema->addError(new sfValidatorError($this, 'cas_particulier_vendeur_union'), 'cas_particulier');
            $hasError = true;
        }

        if ($hasError) {
                throw new sfValidatorErrorSchema($this, $errorSchema);
        }
        return $values;
    }

}
