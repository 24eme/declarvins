<?php
class VracConditionCivpForm extends VracConditionForm
{
    public function configure() {
        parent::configure();
        unset($this['reference_contrat_pluriannuel'], $this['premiere_mise_en_marche']);
    }
}
